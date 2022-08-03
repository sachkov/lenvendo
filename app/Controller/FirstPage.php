<?php
namespace App\Controller;
use App\Http;
use App\Service;


class FirstPage extends Common
{
    protected $middlewareCommon = ['auth'];

    protected $drawing;
    protected $user;

    public function __construct(Service\PrizeDrawing $drawing, Service\User $user)
    {
        $this->drawing = $drawing;
        $this->user = $user;
    }
    
    protected function GET():Http\Response
    {
        $data = [
            'user'=>$this->request->get('user')
        ];

        //Принимал ли пользователь участие в розыгрыше?
        $data['prize'] = [];
        if(isset($data['user']) && isset($data['user']['id'])){
            $data['prize'] = $this->drawing->getLastPrize($data['user']['id']);
        }

        $this->response->setTemplate('index',$data);

        return $this->response;
    }

    protected function POST():Http\Response
    {
        $request = $this->request->get('request');

        $login = $this->user->login($request);

        $data = [
            'user'=>$login
        ];

        //Принимал ли пользователь участие в розыгрыше?
        $data['prize'] = [];
        if(isset($data['user']) && isset($data['user']['id'])){
            $data['prize'] = $this->drawing->getLastPrize($data['user']['id']);
        }

        $this->response->setTemplate('index',$data);

        return $this->response;
    }
}