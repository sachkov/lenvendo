<?php
namespace App\Migrations;
use App;

/**
 * Создание основной таблицы пользователей
 */

class Auth
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        $table_name = 'users';

        // Проверка на существование таблицы
        $sql = "
            SELECT count(*)
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
        ";
        $bind = ['test', $table_name];

        if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');

        $resultSetPrev = $this->app::$db->executeQuery($sql, $bind);
        $existPrev = (int)$resultSetPrev->fetchOne();

        if($existPrev){
            echo "Table $table_name already exist.\n";
            return false;
        }
       
        $sql = "
            CREATE TABLE IF NOT EXISTS `" . $table_name . "`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `login` CHAR(32) NOT NULL COMMENT 'Логин пользователя',
                `password` VARCHAR(255) NOT NULL COMMENT 'хэш пароля',
                PRIMARY KEY `id`(`id`),
                UNIQUE KEY `login`(`login`)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8  COLLATE=utf8_unicode_ci
            COMMENT='Основная таблица пользователей';
        ";

        $this->app::$db->executeStatement($sql);

        // Проверка на существование таблицы
        $resultSetPost = $this->app::$db->executeQuery($sql, $bind);
        $existPost = (int)$resultSetPost->fetchOne();

        if($existPost && !$resultSetPrev){
            echo "Table $table_name successfully created.\n";
        }
    }

}