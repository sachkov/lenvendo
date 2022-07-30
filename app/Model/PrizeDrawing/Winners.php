<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей призов
 */
class Winners extends Model\Common
{
    protected $table = 'winners';
    protected $fields = [
        'id',
        'created_at',
        'user_id',
        'prize_id',
        'draw_id'
    ];

    /**
     * Сколько призов было разыграно по типам
     */
    public function getResidueByDraw(int $draw_id)
    {
        if(!$draw_id) return [];

        $db = $this->getDb();

        $sql = "
            SELECT `prize_id`, count(id) 
            FROM $this->table
            WHERE `draw_id` = ?
            GROUP BY `prize_id`
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $draw_id);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAllAssociativeIndexed();

        if(!$res) return [];
        return $res; 
    }

}