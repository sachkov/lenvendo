<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов
 */

class PrizeDrawing
{
    protected $app;

    public function handle()
    {
        $this->app = new App\Application;

        try{
            $this->createDrawTable();
            $this->createPrizeTable();
            $this->createPrizeTypesTable();
            $this->createWinnersTable();
        }catch(\Exception $e){
            echo "Prize Drawing tables creation errors!.\n";
            echo $e->getMessage()."\n";
        }
    }

    private function createDrawTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `prize_drawing`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL COMMENT 'Название розыгрыша',
                `code` VARCHAR(32) NOT NULL COMMENT 'Код розыгрыша',
                `active` tinyint(1) DEFAULT NULL COMMENT 'активен ли розыгрыш',
                PRIMARY KEY `id`(`id`),
                UNIQUE (`code`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица розыгрышей';
        ";

        $this->app::$db->executeStatement($sql);
    }

    private function createPrizeTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `prizes`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL COMMENT 'Название приза',
                `value` INT(11) UNSIGNED COMMENT 'Ценность приза',
                `prize_type_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД типа приза',
                `draw_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД розыгрыша',
                `amount` INT(11) UNSIGNED NOT NULL COMMENT 'Кол-во призов в розыгрыше',
                `multiplexer` INT(11) UNSIGNED NOT NULL COMMENT 'мультиплексор для рассчета % выигрыша',
                PRIMARY KEY `id`(`id`),
                FOREIGN KEY (`prize_type_id`) REFERENCES `prize_types`(`id`),
                FOREIGN KEY (`draw_id`) REFERENCES `prize_drawing`(`id`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица призов';
        ";

        $this->app::$db->executeStatement($sql);
    }

    private function createPrizeTypesTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `prize_types`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL COMMENT 'Название типа приза',
                `code` VARCHAR(32) NOT NULL COMMENT 'Код типа приза',
                PRIMARY KEY `id`(`id`),
                UNIQUE (`code`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица типов призов';
        ";

        $this->app::$db->executeStatement($sql);
    }

    private function createWinnersTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `winners`(
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `created_at` DATETIME NOT NULL COMMENT 'Дата выигрыша',
                `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД пользователя',
                `prize_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД приза',
                `draw_id` INT(11) UNSIGNED NOT NULL COMMENT 'ИД розыгрыша',
                PRIMARY KEY `id`(`id`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                FOREIGN KEY (`prize_id`) REFERENCES `prizes`(`id`),
                FOREIGN KEY (`draw_id`) REFERENCES `prize_drawing`(`id`)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
            COMMENT='Таблица победителей розыгрыша';
        ";

        $this->app::$db->executeStatement($sql); 
    }

}