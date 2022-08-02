<?php
namespace App\Model\PrizeDrawing;

use App\Model;

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

}