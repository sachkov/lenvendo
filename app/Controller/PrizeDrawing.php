<?php
namespace App\Controller;
use App\Http;
use App\Service;


class PrizeDrawing extends Common
{
    protected $middlewareCommon = ['auth'];

    public function __construct(Service\PrizeDrawing $drawing)
    {
        $this->drawing = $drawing;
    }
    
    protected function GET():Http\Response
    {
        $user = $this->request->get('user');

        // Ранее полученный приз
        $prize = $this->drawing->getLastPrize($user['id']);

        // Получить новый приз
        if(!$prize){
            $prize = $this->drawing->setPrizeTo($user);
        }

        // Получить действие, которое выбрал пользователь для приза
        $prizeAction = $this->drawing->getPrizeAction($prize);

        $data = [
            'user'          => $user,
            'prize'         => $prize,
            'prizeAction'   => $prizeAction
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }

    /**
     * Выбор действия с призом
     */
    protected function POST():Http\Response
    {
        $user = $this->request->get('user');
        $request = $this->request->get('request');

        // Ранее полученный приз
        $prize = $this->drawing->getLastPrize($user['id']);

        $this->drawing->handleAction($request, $prize);

        // Получить действие, которое выбрал пользователь для приза
        $prizeAction = $this->drawing->getPrizeAction($prize);

        $data = [
            'user'          => $user,
            'prize'         => $prize,
            'prizeAction'   => $prizeAction
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }
}