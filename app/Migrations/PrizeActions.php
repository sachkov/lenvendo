<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов 2
 */

class PrizeActions
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        try{
            if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');
            $this->createPrizeActionsTbl();
            $this->createPrizeActionslogTbl();
        }catch(\Exception $e){
            echo "Prize Actions tables creation errors!.\n";
            echo $e->getMessage()."\n";
        }
    }

    private function createPrizeActionsTbl()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `prize_actions`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL COMMENT 'Название действия',
                `code` VARCHAR(32) NOT NULL COMMENT 'Код действия',
                `description` TEXT COMMENT 'Описание действия',
                `prize_type_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД типа приза',
                `draw_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД розыгрыша',
                PRIMARY KEY `id`(`id`),
                FOREIGN KEY (`prize_type_id`) REFERENCES `prize_types`(`id`),
                FOREIGN KEY (`draw_id`) REFERENCES `prize_drawing`(`id`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица действий с призами';
        ";

        $this->app::$db->executeStatement($sql);
    }

    private function createPrizeActionslogTbl()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `prize_actions_log`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `created_at` DATETIME NOT NULL COMMENT 'Дата действия',
                `prize_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД  приза',
                `prize_action_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД действия',
                PRIMARY KEY `id`(`id`),
                FOREIGN KEY (`prize_id`) REFERENCES `prizes`(`id`),
                FOREIGN KEY (`prize_action_id`) REFERENCES `prize_actions`(`id`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица лога действия с призом';
        ";

        $this->app::$db->executeStatement($sql);
    }

}