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
        $prize = $this->drawing->getUserPrize($user['id']);

        // Получить новый приз
        if(!$prize){
            $prize = $this->drawing->setPrizeTo($user);
        }

        // Получить действие, которое выбрал пользователь для приза
        $prizeAction = $this->drawing->getPrizeActionResult($prize['id']);

        // Получить возможные действия с призом
        if(!$prizeAction){
            $prizeAction = $this->drawing->getPrizeAction($prize);
        }

        $data = [
            'user'          => $this->request->get('user'),
            'prize'         => $prize,
            'prizeAction'   => $prizeAction
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }
}