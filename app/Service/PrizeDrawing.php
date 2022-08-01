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
     * Найти случайный приз
     */
    public function getRundomPrize()
    {
        $draw_id = $this->draw->getActive();

        if(!$draw_id){
            return ['error'=>'No active drawing right now.'];
        }

        $prizes = $this->prizes->getPrizeValue($draw_id);

        if(!isset($prizes['multiplexer']) || !$prizes['multiplexer']){
            return $this->draw->endDrawing($draw_id);
        }

        $max = $prizes['multiplexer'];

        $win = random_int(0, $max);

        $win_prize = 0;

        foreach($prizes['prizes'] as $k=>$prize){
            if($win >= $prize['mult_min'] && $win <= $prize['mult_max']){
                $win_prize = $prize['prize_id'];
                break;
            }
        }

        return $win_prize;
    }
}