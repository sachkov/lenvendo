<?php
namespace App\Controller;
use App\Http;
use App\Service;


class FirstPage extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {
        echo 'DB_HOST:'.getenv('DB_HOST'). " DB_PORT:".getenv('DB_PORT');
        $data = [
            'user'=>$this->request->get('user')
        ];

        $this->response->setTemplate('index',$data);

        return $this->response;
    }

    protected function POST():Http\Response
    {
        $request = $this->request->get('request');

        $user = \App\Application::$container->get(Service\User::class);

        $login = $user->login($request);

        $data = [
            'user'=>$login
        ];

        $this->response->setTemplate('index',$data);

        return $this->response;
    }
}