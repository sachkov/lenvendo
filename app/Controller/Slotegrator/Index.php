<?php
namespace App\Controller\Slotegrator;

use App\Http;
use App\Service;
use App\Controller;

class Index extends Controller\Common
{
    protected $middlewareCommon = ['auth'];

    protected $drawing;
    protected $user;

    public function __construct(Service\PrizeDrawing $drawing, Service\User $user)
    {
        $this->drawing = $drawing;
        $this->user = $user;
    }
    
    /**
     * Кнопка Эпринять участие в розыгрыше
     */
    protected function GET():Http\ResponseInterface
    {
        $data = [
            'user'=>$this->request->get('user')
        ];

        //Принимал ли пользователь участие в розыгрыше?
        $data['prize'] = [];
        if(isset($data['user']) && isset($data['user']['id'])){
            $data['prize'] = $this->drawing->getLastPrize($data['user']['id']);
        }

        $this->response->setTemplate('slotegrator_index',$data);

        return $this->response;
    }

    /*
    protected function POST():Http\ResponseInterface
    {
        //$request = $this->request->get('request');

        //$login = $this->user->login($request);

        //$data = [
        //    'user'=>$login
        //];

        $data = [
            'user'=>$this->request->get('user')
        ];

        //Принимал ли пользователь участие в розыгрыше?
        $data['prize'] = [];
        if(isset($data['user']) && isset($data['user']['id'])){
            $data['prize'] = $this->drawing->getLastPrize($data['user']['id']);
        }

        $this->response->setTemplate('slotegrator_index',$data);

        return $this->response;
    }
    */
}