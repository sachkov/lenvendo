<?php
namespace App\Model\Tabor;

use App\Model;
use Doctrine\DBAL;


/**
 * Класс для работы с таблицей призов
 */
class Sum extends Model\Common
{
    protected $table = 'tabor_sum';
    protected $fields = [
        'id',
        'created_at',
        'value',
        'idempotence',
        'sum'
    ];

    /**
     * Возвращаем результат, добавления числа
     * @param int value column
     * @return int sum column
     */
    public function getSumByVal(int $val):int
    {
        $db = $this->getDb();

        $sql = "
            SELECT `sum`
            FROM $this->table
            WHERE `value` = ?
            AND `idempotence` = '1'
            ORDER BY `created_at` DESC
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $val);
        $resultSet = $stmt->executeQuery();

        return (int)$resultSet->fetchOne();
    }

    /**
     * Добавляем новое значение и возвращаем сумму добавленных чисел
     */
    public function addNewVal(int $idemVal, bool $idemKey)
    {
        $db = $this->getDb();

        $db->setTransactionIsolation(DBAL\TransactionIsolationLevel::SERIALIZABLE);
        $db->beginTransaction();

        try{
            //Получаем последнюю сумму
            $sum = $this->getSum();

            $sum = $sum + $idemVal;

            $db->insert($this->table, [
                'created_at'    => date('Y-m-d H:i:s'),
                'value'         => $idemVal,
                'idempotence'   => (int)$idemKey,
                'sum'           => $sum
            ]);

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }

        return $sum??false;
    }

    /**
     * Сумма всех чисел в базе
     * @return int
     */
    protected function getSum():int
    {
        $db = $this->getDb();

        $sql = "
            SELECT `sum`
            FROM $this->table
            ORDER BY `id` DESC
            LIMIT 1
        ";

        return (int)$db->fetchOne($sql);
    }
}
