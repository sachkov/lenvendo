<?php
namespace App\Service\PrizeAction;

use App\Model;

/**
 * Обработчик действий при выигрыше денежного приза
 */
class RubAction extends Common implements ActionHandlerInterface
{
    public function handleAction(array $action, array $prize)
    {
        if($action['code'] == 'change_to_points'){
            $userPointsModel = new Model\UserPoints;
            $winnersModel = new Model\PrizeDrawing\Winners;

            $winner = $winnersModel->getById($action['win_id']);

            $userPointsModel->transfer($winner['user_id'], $prize['value']);
        }elseif($action['code'] == 'transfer'){
            $winnersModel = new Model\PrizeDrawing\Winners;

            $winner = $winnersModel->getById($action['win_id']);

            //Записать в таблицу на перевод
        }
    }

}