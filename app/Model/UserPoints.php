<?php
namespace App\Model;


/**
 * Класс для работы с таблицей баллов пользователей
 */
class UserPoints extends Common
{
    protected $table = 'user_points';
    protected $fields = [
        'id',
        'user_id',
        'value'
    ];

    public function transfer(int $userId, int $value)
    {
        $db = $this->getDb();

        $db->beginTransaction();

        try{
            //Получить кол-во выданных призов
            $count = $this->getUserPoints($userId);

            $total = $count + $value;
            if($total < 0) 
                throw new \Exception('User ID='.$userId.' have no points');

            $db->update($this->table, [
                'user_id'   => $userId,
                'value'     => $total
            ]);

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }

        return true;
    }

    public function getUserPoints(int $userId):int
    {
        if(!$userId) return 0;

        $db = $this->getDb();

        $sql = "
            SELECT `value` 
            FROM $this->table
            WHERE `user_id` = ?
        ";

        $res = $db->fetchOne($sql,[$userId]);

        return (int)$res;
    }
}