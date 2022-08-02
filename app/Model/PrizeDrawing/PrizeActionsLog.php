<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей лога действий, выполненных над призом
 */
class PrizeActionsLog extends Model\Common
{
    protected $table = 'prize_actions_log';
    protected $fields = [
        'id',
        'created_at',
        'prize_id',
        'prize_action_id'
    ];

}