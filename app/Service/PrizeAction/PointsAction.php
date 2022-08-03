<?php
namespace App\Service\PrizeAction;

use App\Model;

/**
 * Обработчик действий при выигрыше бонусных баллов
 */
class PointsAction extends Common implements ActionHandlerInterface
{
    public function handleAction(array $action, array $prize)
    {
        if($action['code'] == 'credit_to_account'){
            $userPointsModel = new Model\UserPoints;
            $winnersModel = new Model\PrizeDrawing\Winners;

            $winner = $winnersModel->getById($action['win_id']);

            $userPointsModel->transfer($winner['user_id'], $prize['value']);
        }
    }
}