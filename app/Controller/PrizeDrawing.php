<?php
namespace App\Controller;
use App\Http;
use App\Service;
use App\Service\PrizeAction;


class PrizeDrawing extends Common
{
    protected $middlewareCommon = ['auth'];

    protected $drawing;
    protected $actionHandler;

    public function __construct(Service\PrizeDrawing $drawing, PrizeAction\Common $actionHandler)
    {
        $this->drawing = $drawing;
        $this->actionHandler = $actionHandler;
    }
    
    protected function GET():Http\ResponseInterface
    {
        $user = $this->request->get('user');

        // Ранее полученный приз
        $prize = $this->drawing->getLastPrize($user['id']);

        // Получить новый приз
        if(!$prize){
            $prize = $this->drawing->setPrizeTo($user);
        }

        // Получить действие, которое выбрал пользователь для приза
        $prizeAction = $this->actionHandler->getActions($prize);

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
    protected function POST():Http\ResponseInterface
    {
        $user = $this->request->get('user');
        $request = $this->request->get('request');

        // Ранее полученный приз
        $prize = $this->drawing->getLastPrize($user['id']);

        // Выполнить действие которое запросил пользователь
        if(isset($request['choise']) && isset($prize['win_id'])){
            $action = $this->actionHandler->getActionByCode($request['choise']);

            if($action) $this->actionHandler->setActionLog($action, $prize);
        };

        // Получить действие, которое выбрал пользователь для приза
        $prizeAction = $this->actionHandler->getActions($prize);

        $data = [
            'user'          => $user,
            'prize'         => $prize,
            'prizeAction'   => $prizeAction
        ];

        $this->response->setTemplate('drawing',$data);

        return $this->response;
    }
}