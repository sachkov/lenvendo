<?php
namespace App\Controller;
use App\Http;
use App\Service;


class PrizeDrawing extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {
        $user = $this->request->get('user');

        $drawing = \App\Application::$container->get(Service\PrizeDrawing::class);

        //$prize = $drawing->getRandomPrize();

        $prize = $drawing->setPrizeTo($user);

        $data = [
            'user'=>$this->request->get('user')
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }
}