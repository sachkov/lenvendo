<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей типов призов
 */
class PrizeTypes extends Model\Common
{
    protected $table = 'prize_types';
    protected $fields = [
        'id',
        'name',
        'code'
    ];

    /**
     * Получить код действия по ИД розыгрыша и ИД типа приза
     * @param int $prizeTypeId ИД типа приза
     * @return string код действия
     */
    public function getCodeByPrizeTypeId(int $prizeTypeId):string
    {
        if(!$prizeTypeId) return '';
        
        $db = $this->getDb();

        $sql = "
            SELECT `code` 
            FROM $this->table
            WHERE `id` = ?
        ";

        $code = $db->fetchOne($sql, [$prizeTypeId]);

        return $code;
    }
}