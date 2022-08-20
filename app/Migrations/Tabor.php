<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для ТЗ Табор
 */

class Tabor
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        try{
            if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');
            $this->createTbl();
        }catch(\Exception $e){
            echo "Tabor tables creation errors!.\n";
            echo $e->getMessage()."\n";
        }
    }

    private function createTbl()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `tabor_sum`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `created_at` DATETIME NOT NULL COMMENT 'Дата внесения данных',
                `value` INT(11) NOT NULL COMMENT 'Добавляемое значение',
                `idempotence` tinyint(1) DEFAULT 0 COMMENT 'Имподенсный запрос или нет',
                `sum` INT(11) NOT NULL COMMENT 'Сумма всех значений',
                PRIMARY KEY `id`(`id`),
                KEY `value`(`value`)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица проверки имподенса и гонки процессов';
        ";

        $this->app::$db->executeStatement($sql);
    }
}