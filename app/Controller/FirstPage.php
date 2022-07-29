<?php
namespace App\Controller;
use App\Http;
use App\Service;


class FirstPage extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {
        $request = $this->request->get('query');

        var_dump($request);
        $data = [
            'd'=>'demo', 
            //'user'=>['login'=>'vasya'],
            'request'=> $request,
            'method'=> 'GET'
        ];

        $this->response->setTemplate('index',$data);

        return $this->response;
    }

    protected function POST():Http\Response
    {
        $request = $this->request->get('request');

        $user = \App\Application::$container->get(Service\User::class);

        var_dump($request);
        $data = [
            'd'=>'demo', 
            //'user'=>['login'=>'vasya'],
            'method'=> 'POST'
        ];

        $this->response->setTemplate('index',$data);

        return $this->response;
    }
}