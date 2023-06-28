<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Session\Manager;
use Phalcon\Http\Response\Cookies;
use time\Time;
use Phalcon\Logger;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Escaper;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once BASE_PATH. '/vendor/autoload.php';

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
        APP_PATH . "/etc/",
    ]
);
// registering namespace
$loader->registerNamespaces(
    [
        'time' => APP_PATH . "/assets/",


    ]
);
$loader->registerClasses([
    'Myescaper' => APP_PATH . '/component/Myescaper.php',
]);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);



$container->set(
    'db',
    function () {
        return new Mysql($this['config']->db->toArray());
    }
);
$container->set(
    'config',
    function () {
        $fileName = '../app/etc/config.php';
        $factory = new ConfigFactory();
        return $config = $factory->newInstance('php', $fileName);
    }
);


$container->setShared(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session->setAdapter($files)->start();
        return $session;
    }

);
$container->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(false);
    return $cookies;
});
$container->set(
    'time',
    function () {
        return new Time();
    }
);
$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);

$container->set(
    'logger',
    function () {

        $adapter1 = new Phalcon\Logger\Adapter\Stream(APP_PATH . '/storage/logs/access.log');
        $adapter2 = new Phalcon\Logger\Adapter\Stream(APP_PATH . '/storage/logs/error.log');
        $logger =   new Logger(
            'messages',
            [
                'access' => $adapter1,
                'error' => $adapter2,
            ]
        );

        return $logger;
    }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://deekshapandey:Deeksha123@cluster0.whrrrpj.mongodb.net/?retryWrites=true&w=majority'
        );

        return $mongo->chatapp;
    },
    true
);


$application = new Application($container);

$response = $application->handle(
    $_SERVER["REQUEST_URI"]
);
$response->send();
