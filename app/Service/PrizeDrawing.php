<?php
namespace App\Service;

use App\Model\PrizeDrawing as ModelDrawing;
use App\Service\PrizeAction;

class PrizeDrawing
{
    protected $winners;
    protected $draw;
    protected $prizes;

    public function __construct(
        ModelDrawing\Winners $winners, 
        ModelDrawing\PrizeDrawing $draw,
        ModelDrawing\Prizes $prizes

    ){
        $this->winners = $winners;
        $this->draw = $draw;
        $this->prizes = $prizes;
    }

    /**
     * Получить приз пользователя в текущем розыгрыше
     * @param int $userId ИД пользователя
     * @return array|bool массив с описанием приза 
     * или false если приз еще не получен
     */
    public function getLastPrize(int $userId)
    {
        if(!$userId) return false;

        $drawId = $this->draw->getActiveId();

        if(!$drawId) return false;

        $prize = $this->winners->getPrize($userId, $drawId);

        if(!$prize) return false;
        $prize['draw_id'] = $drawId;

        return $prize;
    }

    /**
     * Присвоить случайный приз пользователю
     * @param array $user массив с данными пользователя
     * @return array|false массив с данными приза или false в случае ошибки
     */
    public function setPrizeTo(array $user)
    {
        $res = false;
        while(!$res){
            // Получаем случайный приз из не выданных
            $prize = $this->getRundomPrize();
            // Назначаем(выдаем) приз пользователю
            $res = $this->winners->commit($user, $prize);
        }
        // Получаем данные приза
        $userPrize = $this->winners->getPrize($user['id'], $prize['draw_id']);

        return $userPrize;
    }

    /**
     * Найти случайный приз
     * @return array вида ["id"=>int,"name"=>string,"value"=>int,"prize_type_id"=>int,"draw_id"=>int,
     * "amount"=>int,"multiplexer"=>int,"residue"=>int,"mult_min"=>int,"mult_max"=>int]
     */
    public function getRundomPrize()
    {
        $drawId = $this->draw->getActiveId();

        if(!$drawId){
            return ['error'=>'No active drawing right now.'];
        }

        $prizes = $this->getPrizeValue($drawId);

        if(!isset($prizes['multiplexer']) || !$prizes['multiplexer']){
            return $this->draw->endDrawing($drawId);
        }

        $max = $prizes['multiplexer'];

        $win = random_int(0, $max);

        $winPrize = [];

        // можно поменять на выборку с двоичным поиском
        foreach($prizes['prizes'] as $k=>$prize){
            if($win >= $prize['mult_min'] && $win <= $prize['mult_max']){
                $winPrize = $prize;
                $winPrize['z'] = $drawId;
                break;
            }
        }

        return $winPrize;
    }

    /**
     * Получение списка призов с мультиплексом значений для
     * рассчета розыгрыша по остаткам
     * @param int ИД розыгрыша
     * @return array массив вида [prizes=>[список призов],multiplexer=>int]
     */
    protected function getPrizeValue(int $drawId):array
    {
        if(!$drawId) return [];

        $prizes = $this->prizes->getPrizesByDraw($drawId);

        $residue = $this->winners->getResidueByDraw($drawId);

        $multiplexer = 0; //cумма всех мультиплексоров призов * остаток
        foreach($prizes as $k=>$prize){
            if(!isset($residue[$prize['id']]) || !$residue[$prize['id']]) $rest = 0;
            else $rest = $residue[$prize['id']];

            //остаток
            $prizes[$k]['residue'] = $prize['amount'] - $rest;
            
            if($prizes[$k]['residue'] <= 0) continue;

            //мультиплексор
            $prizes[$k]['mult_min'] = $multiplexer;
            $multiplexer += $prizes[$k]['residue'] * $prize['multiplexer'];
            $prizes[$k]['mult_max'] = $multiplexer-1;

        }
        $res['prizes'] = $prizes;
        $res['multiplexer'] = $multiplexer;

        return $res;
    }

}