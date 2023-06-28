<?php

use Phalcon\Mvc\Controller;


class SignupController extends Controller
{

    public function IndexAction()
    {
        // defalut action


    }

    public function registerAction()
    {
        
      
        if ($this->request->isPost()) {
            $name =$this->escaper->escapeHtml($this->request->getPost('name'));
            $email = $this->escaper->escapeHtml($this->request->getPost('email'));
            $password = $this->escaper->escapeHtml($this->request->getPost('password'));


            $collection = $this->mongo->Users;
            $data = $collection->insertOne(["name" => $name,
             "email" => $email, "password" => $password]);

             
             $this->session->set('name', $name);
             $this->session->set('email', $email);
        $this->session->set('password', $password);
        
        
        if ($_POST['remember']=="on") {
            $this->cookies->set('email', $this->session->get('email'), time() + 15 * 86400);
            $this->cookies->set('pass', $this->session->get('password'), time() + 15 * 86400);
        } else {
            $this->cookies->set('email', $this->session->get('email'), time() - 15 * 86400);
            $this->cookies->set('pass', $this->session->get('password'), time() - 15 * 86400);
        }
        
        if ($data->getInsertedCount() == 1) {
            $this->response->redirect("login");
        } else {
            echo "Invalid details found";
            die;
        }

    }
}
}