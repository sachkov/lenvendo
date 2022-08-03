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
        'winner_id',
        'prize_action_id'
    ];

    public function getLogPrize(int $winnerId)
    {
        $db = $this->getDb();

        $sql = "
            SELECT $this->table.`created_at`, `prize_actions`.*
            FROM $this->table
            LEFT JOIN `prize_actions`
                ON $this->table.`prize_action_id` = `prize_actions`.`id`
            WHERE `winner_id` = ?
        ";

        return  $db->fetchAssociative($sql, [$winnerId]);
    }

    public function setLog($actionId, $winId)
    {
        if(!$actionId || !$winId) return false;

        $db = $this->getDb();

        $db->insert($this->table, [
            'created_at'      => date('Y-m-d H:i:s'),
            'winner_id'       => $winId,
            'prize_action_id' => $actionId
        ]);
    }

}