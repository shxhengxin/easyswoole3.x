<?php


namespace App\HttpController\Admin;


use App\HttpController\AdminController;
use App\Server\RequestMethod;
use App\Server\Upload\UploadService;
use App\Server\VerifyCode\CodeService;
use EasySwoole\Http\Message\Status;
use EasySwoole\Pool\Manager;
use EasySwoole\Validate\Validate;
use EasySwoole\VerifyCode\Conf;
use EasySwoole\VerifyCode\VerifyCode;


class Login extends AdminController
{
    public function login()
    {
        if($this->request()->getMethod() == RequestMethod::POST) {
            var_dump(111);
        }

        $this->render('login');
    }

    public function register()
    {
        if($this->request()->getMethod() == RequestMethod::POST) {

        }
        $this->render('register');
    }

    public function upload()
    {
        $data = UploadService::upload($this->request()->getUploadedFile("file"));
        return $this->writeJson($data['status'],$data['list'],$data['msg']);
    }


    public function code()
    {
        $params = $this->request()->getRequestParam();
        if(empty($params['key'])) return $this->writeJson(Status::CODE_BAD_REQUEST,"","key不能为空");
        $code = CodeService::getCode($params['key']);
        $this->response()->withHeader('Content-Type','image/png');
       return $this->response()->write($code->getImageByte());




    }
    
    protected function validateRule(?string $action):?Validate
    {
        $method = $this->request()->getMethod();
        $v = new Validate();
        if($method == RequestMethod::POST || $method == RequestMethod::PUT) {
            switch ($action){
                case 'login':
                    $v->addColumn('username','用户名')->required("不能为空");
                    $v->addColumn('password','密码')->required("不能为空")->between(6,12,"密码只能在6到12位数");
                    break;
            }
        }
        return  $v;


    }


}