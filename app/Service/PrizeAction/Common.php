<?php
namespace App\Service\PrizeAction;

use App\Model\PrizeDrawing;

/**
 * Фабрика для создания обработчиков действий приза
 */
class Common
{
    protected $prizeTypesModel;
    protected $prizeActionsModel;
    protected $prizeActionsLogModel;

    protected $prize;

    public function __construct(
        PrizeDrawing\PrizeTypes $prizeType,
        PrizeDrawing\PrizeActions $prizeActions,
        PrizeDrawing\PrizeActionsLog $prizeActionsLog
    ){
        $this->prizeTypesModel = $prizeType;
        $this->prizeActionsModel = $prizeActions;
        $this->prizeActionsLogModel = $prizeActionsLog;
    }

    /**
     * Получить действия, которые пользователь может сделать с призом
     * @param array $prize - массив с информацией о призе 
     * @return array вида [name=>string,description=>string,choice=>string]
     */
    public function getActions(array $prize)
    {
        //$handler = $this->getHandler($prize['prize_type_id']);

        $doneActions = $this->prizeActionsLogModel->getLogPrize($prize['win_id']);

        if($doneActions) return $doneActions;

        return $this->prizeActionsModel->getActions($prize['prize_type_id'], $prize['draw_id']);
    }

    public function getActionByCode(string $code)
    {
        if(!$code) return false;

        return $this->prizeActionsModel->getByCode($code);
    }

    public function setActionLog(array $action, array $prize)
    {
        if(!isset($action['id']) || !isset($prize['win_id'])) return false;

        $handler = $this->getHandler($prize['prize_type_id']);

        if($handler) $handler->handleAction($action, $prize);

        $this->prizeActionsLogModel->setLog($action['id'], $prize['win_id']);
    }

    /**
     * Получить обработчик действия приза по ИД приза
     */
    private function getHandler(int $prizeTypeId)
    {
        $actionCode = $this->prizeTypesModel->getCodeByPrizeTypeId($prizeTypeId);

        if(!$actionCode){
            return false;
            //throw new \Exception('No Prize Type found for prize_type_id='.$prizeTypeId);
        }

        $clName = ucfirst(strtolower($actionCode))."Action";
        
        if(class_exists($clName)){
            return new $clName;
        }else{
            return false;
            //throw new \Exception('No Handler found for prize_type_id='.$prizeTypeId);
        }


    }

}