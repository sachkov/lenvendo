<?php
namespace App\Model\PrizeDrawing;

use App\Model;
use Doctrine\DBAL;

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
     * @param int $drawId - ИД розыгрыша
     * @return array - массив вида ['prize_id'=>(int),'count'=>(int)] 
     * или [] в случае ошибки
     */
    public function getResidueByDraw(int $drawId):array
    {
        if(!$drawId) return [];

        $db = $this->getDb();

        $sql = "
            SELECT `prize_id`, count(id) as count
            FROM $this->table
            WHERE `draw_id` = ?
            GROUP BY `prize_id`
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $drawId);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAllAssociative();

        $result = [];
        foreach($res as $row){
            $result[$row['prize_id']] = $row['count'];
        }

        if(!$res) return [];
        return $res; 
    }

    /**
     * Запись о призе который получил пользователь в розыгрыше
     * @param int $userId - ИД пользователя
     * @param int $drawId - ИД розыгрыша
     * @return array|false - запись о призе или false
     */
    public function getPrize(int $userId, int $drawId)
    {
        if(!$userId || !$drawId) return false;

        $db = $this->getDb();

        $sql = "
            SELECT $this->table.`id` as 'win_id', $this->table.`created_at`, `prizes`.* 
            FROM $this->table
            LEFT JOIN `prizes`
                ON $this->table.`prize_id` = `prizes`.`id`
            WHERE $this->table.`user_id` = ?
                AND $this->table.`draw_id` = ?
            LIMIT 1
        ";

        $win = $db->fetchAssociative($sql,[$userId, $drawId]);

        return $win;
    }

    /**
     * Запись информации о призе
     * @param array $user - информация о пользователе
     * @param array $prize - информация о призе и ИД розыгрыша
     * @return array|false - входящий массив $prize или false в случае ошибки
     */
    public function commit(array $user, array $prize)
    {
        $db = $this->getDb();

        $db->setTransactionIsolation(DBAL\TransactionIsolationLevel::SERIALIZABLE);
        $db->beginTransaction();

        try{
            //Получить кол-во выданных призов
            $count = $this->getAwardedPrizeCount($prize['draw_id'], $prize['id']);

            if(intval($prize['amount']) <= $count) 
                throw new \Exception('All prizes '.$prize['name'].' was awarded.');

            $db->insert($this->table, [
                'created_at'    => date('Y-m-d H:i:s'),
                'user_id'       => $user['id'],
                'prize_id'      => $prize['id'],
                'draw_id'       => $prize['draw_id']
            ]);

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }

        return $prize;
    }

    /**
     * Получить кол-во призов, выданных в текущем розыгрыше
     * @param int $drawId - ИД розыгрыша
     * @param int $prizeId - ИД приза
     * @return int кол-во выданных призов
     */
    public function getAwardedPrizeCount(int $drawId, int $prizeId):int
    {
        if(!$drawId || !$prizeId) return 0;

        $db = $this->getDb();

        $sql = "
            SELECT count(id) 
            FROM $this->table
            WHERE `draw_id` = ?
            AND `prize_id` = ?
            GROUP BY `prize_id`
        ";

        $res = $db->fetchOne($sql, [$drawId, $prizeId]);

        return (int)$res;
    }
}