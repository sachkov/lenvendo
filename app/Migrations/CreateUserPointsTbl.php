<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов
 */

class CreateUserPointsTbl
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');

        $sql = "
            CREATE TABLE IF NOT EXISTS `user_points`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД пользователя',
                `value` INT(11) UNSIGNED NOT NULL COMMENT 'Количество баллов',
                PRIMARY KEY `id`(`id`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица хранения баллов пользователей';
        ";

        $this->app::$db->executeStatement($sql);
    }
}