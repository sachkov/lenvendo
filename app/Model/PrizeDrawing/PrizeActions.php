<?php
namespace App\Model\PrizeDrawing;

use App\Model;
use Doctrine\DBAL;

/**
 * Класс для работы с таблицей возможных дейсвий с призом
 */
class PrizeActions extends Model\Common
{
    protected $table = 'prize_actions';
    protected $fields = [
        'id',
        'name',
        'code',
        'description',
        'prize_type_id',
        'draw_id'
    ];

    /**
     * Найти все действия для заданного розыгрыша и типа приза
     */
    public function getActions(int $prizeTypeId, int $drawId)
    {
        $db = $this->getDb();

        $sql = "
            SELECT * 
            FROM $this->table
            WHERE `draw_id` = ?
            AND `prize_type_id` = ?
        ";

        $res = $db->fetchAllAssociative($sql, [$drawId, $prizeTypeId]);

        return $res;
    }

    public function getByCode(string $code)
    {
        $db = $this->getDb();

        $sql = "
            SELECT * 
            FROM $this->table
            WHERE `code` = ?
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $code, DBAL\ParameterType::STRING);
        $resultSet = $stmt->executeQuery();
        
        return $resultSet->fetchAssociative();
    }
}