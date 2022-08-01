<?php
namespace App\Service;

use App\Model\PrizeDrawing as ModelDrawing;

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

    public function getLastPrize(int $userId):array
    {
        if(!$userId) return true;

        $drawId = $this->draw->getActive();

        if(!$drawId) return true;

        $prize = $this->winners->getPrize($userId, $drawId);

        if(!$prize) return [];

        return $prize;
    }

    /**
     * Присвоить случайный приз пользователю
     */
    public function setPrizeTo(array $user)
    {
        //get $drawId

        $res = false;
        while(!$res){

            $prize = $this->getRundomPrize();

            $res = $this->winners->commit($user, $prize);
        }

        return $res;
    }

    /**
     * Найти случайный приз
     */
    public function getRundomPrize()
    {
        $drawId = $this->draw->getActive();

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
                $win_prize = $prize;
                $win_prize['draw_id'] = $drawId;
                break;
            }
        }

        return $winPrize;
    }

    /**
     * Получение списка призов с мультиплексом значений для
     * рассчета розыгрыша по остаткам
     */
    protected function getPrizeValue(int $drawId):array
    {
        if(!$drawId) return [];

        $prizes = $this->prizes->getPrizesByDraw($drawId);

        $residue = $this->winners->getResidueByDraw($drawId);

        $multiplexer = 0; //cумма всех мультиплексоров призов * остаток
        foreach($prizes as $k=>$prize){
            if(!isset($residue[$prize['id']])) continue;

            //остаток
            $prizes[$k]['residue'] = $prize['amount'] - $residue['count'];
            
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