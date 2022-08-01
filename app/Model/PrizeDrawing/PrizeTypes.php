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

}