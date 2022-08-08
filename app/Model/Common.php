<?php
namespace App\Model;

/**
 * Общий класс для работы с моделями
 */
abstract class Common
{
    protected $db;

    protected function getDb()
    {
        if(!isset($this->db)){
            $this->db = \App\Application::$db;
        }
        return $this->db;
    }

    /**
     * Получить строку по ИД
     * @param int ИД строки
     * @return array|false массив со строкой таблицы или false если ошибка
     * или такого ИД не существует
     */
    public function getById(int $id)
    {
        if(!$id) return false;

        $db = $this->getDb();

        $sql = "
            SELECT * 
            FROM $this->table
            WHERE `id` = ?
            LIMIT 1
        ";

        $res = $db->fetchAssociative($sql,[$id]);

        return $res;
    }

    /**
     * Получить последнюю строку
     * @return array|false массив со строкой таблицы или false если ошибка
     * или если таблица пустая
     */
    public function getLast()
    {
        $db = $this->getDb();

        $sql = "
            SELECT * 
            FROM $this->table
            ORDER BY `id` DESC
            LIMIT 1
        ";

        $res = $db->fetchAssociative($sql);

        return $res;
    }

    /**
     * Получить случайную строку
     * @return array|false массив со строкой таблицы или false если ошибка
     * или если таблица пустая
     */
    public function getRundom()
    {
        $db = $this->getDb();

        $sql = "
            SELECT * 
            FROM $this->table
            ORDER BY RAND()
            LIMIT 1
        ";

        $res = $db->fetchAssociative($sql);

        return $res;
    }
}