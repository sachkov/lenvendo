<?php
namespace App\Model;

/**
 * Общий класс для работы с моделями
 */
class Common
{
    protected $db;

    protected function getDb()
    {
        if(!isset($this->db)){
            $this->db = \App\Application::$db;
        }
        return $this->db;
    }
}