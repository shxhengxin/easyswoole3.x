<?php


namespace App\HttpController\Admin;


use App\HttpController\AdminController;
use App\Server\RequestMethod;

class Login extends AdminController
{
    public function login()
    {

        if($this->request()->getMethod() == RequestMethod::POST) {

        }

        $this->render('login');
    }
}