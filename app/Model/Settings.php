<?php
namespace App\Model;


/**
 * Класс для работы с таблицей общих настроек проекта
 */
class Settings extends Common
{
    protected $table = 'settings';
    protected $fields = [
        'id',
        'name',
        'code',
        'section',
        'value'
    ];

    /**
     * Получить значение настройки по коду
     */
    public function getByCode(string $code)
    {
        if(!$code) return [];

        $db = $this->getDb();

        $sql = "
            SELECT * FROM $this->table
            WHERE `code` = ?
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $code);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAssociative();

        if(!$res) return [];
        return $res;
    }

    /**
     * Получить все настройки раздела
     */
    public function getBySection(string $section)
    {
        if(!$section) return [];

        $db = $this->getDb();

        $sql = "
            SELECT * FROM $this->table
            WHERE `section` = ?
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $section);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAllAssociative();

        if(!$res) return [];
        return $res;
    }
}