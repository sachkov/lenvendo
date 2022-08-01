<?php
namespace App\Migrations;
use App;

/**
 * Класс для регистрации и запуска миграций приложения
 */

class Common
{
    protected array $list = [
        Auth::class,
        PrizeDrawing::class,
        PrizeDrawingAddFirstDrawing::class,
        CreateSettingsTbl::class
    ];

    public function execute()
    {
        foreach($this->list as $migration){
            if(class_exists($migration)){
                (new $migration())->handle();
            }
        }
    }

}