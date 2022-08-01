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