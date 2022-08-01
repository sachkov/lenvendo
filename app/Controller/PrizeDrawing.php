<?php
namespace App\Controller;
use App\Http;
use App\Service;


class PrizeDrawing extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {
        $drawing = \App\Application::$container->get(Service\PrizeDrawing::class);

        $prize = $drawing->getRandomPrize();

        $data = [
            'user'=>$this->request->get('user')
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }
}