<?php

use Phalcon\Mvc\Controller;

// login controller
class LoginController extends Controller
{
    public function indexAction()
    {
        // default login view
    }
    // login action page
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $password = $this->request->getPost("password");
            $email = $this->request->getPost("email");
        }
    
        $collection = $this->mongo->Users;
        $data = $collection->findOne(["email" => $email, "password" => $password]);
        if ($data->name != "") {
            setcookie("login", $data->_id, time() + 84000, "/");
            $this->logger
            ->excludeAdapters(['error']);
            $this->logger->info("Login succesfully $email.$password");
            $this->view->message = "Login succesfully ";
            $this->response->redirect("/user/index");
        } else {
            $this->logger
            ->excludeAdapters(['access']);
            $this->logger->info("Authentication Failed \"$email\".\"$password\"");
            $this->view->message = "Not Login succesfully ";
            $this->response->redirect('login');
        }
    }
    public function logoutAction()
    {
        setcookie("login", "", time() - 84000, "/");
        $this->response->redirect("/signup/");
    }
}
