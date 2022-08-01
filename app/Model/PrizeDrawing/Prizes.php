<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей призов
 */
class Prizes extends Model\Common
{
    protected $table = 'prizes';
    protected $fields = [
        'id',
        'name',
        'value',            //ценность приза
        'prize_type_id',    //тип приза
        'draw_id',          //ИД розыгрыша
        'amount',           //кол-во призов
        'multiplexer'       //мультиплексор для рассчета % выигрыша
    ];

    protected $winners;

    public function __construct(Winners $win)
    {
        $this->winners = $win;
    }

    /**
     * Получение списка призов, учавствующих в розыгрыше
     */
    public function getPrizesByDraw(int $drawId):array
    {
        if(!$drawId) return [];

        $db = $this->getDb();

        $sql = "
            SELECT * FROM $this->table
            WHERE `draw_id` = ?
            ORDER BY `id`
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $drawId);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAllAssociative();

        if(!$res) return [];
        return $res;
    }

}