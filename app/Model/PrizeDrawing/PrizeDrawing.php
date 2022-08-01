<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей розыгрышей
 */
class PrizeDrawing extends Model\Common
{
    protected $table = 'prize_drawing';
    protected $fields = [
        'id',
        'name',
        'code',
        'active'
    ];

    protected $prizes;
    protected $winners;

    public function __construct(Prizes $prizes, Winners $win)
    {
        $this->prizes = $prizes;
        $this->winners = $win;
    }

    /**
     * Получить активный розыгрыш
     */
    public function getActive():int
    {
        $db = $this->getDb();

        $sql = "
            SELECT * FROM $this->table
            WHERE `active` = 1
            ORDER BY `id`
            LIMIT 1
        ";
        $statement = $db->prepare($sql);
        $resultSet = $statement->executeQuery();
        
        return (int)$resultSet->fetchAssociative();
    }

    /**
     * Найти случайный приз (возможно перенести в сервис)
     */
    public function getRundomPrizeId()
    {
        $draw_id = $this->getActive();

        if(!$draw_id){
            return ['error'=>'No active drawing right now.'];
        }

        $prizes = $this->prizes->getPrizeValue($draw_id);

        if(!isset($prizes['multiplexer']) || !$prizes['multiplexer']){
            return $this->endDrawing($draw_id);
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

    /**
     * Закончить розыгрыш
     */
    public function endDrawing(int $draw_id):array
    {
        if(!$draw_id) return [];

        $db = $this->getDb();

        $db->update($this->table, ['active' => 0], ['id' => $draw_id]);

        return ['error'=>'Draw' . $draw_id . ' is finished.'];
    }
}