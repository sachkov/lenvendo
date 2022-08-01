<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов
 */

class CreateSettingsTbl
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');

        $sql = "
            CREATE TABLE IF NOT EXISTS `settings`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL COMMENT 'Наименование настройки',
                `code` VARCHAR(32) NOT NULL COMMENT 'Код настройки',
                `section` VARCHAR(32) COMMENT 'Раздел настроек',
                `value` INT(11) UNSIGNED NOT NULL COMMENT 'Значение настройки'
                PRIMARY KEY `id`(`id`),
                UNIQUE (`code`)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица общих настроек';
        ";

        $this->app::$db->executeStatement($sql);
    }
}