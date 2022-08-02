<?php
namespace App\Model;


/**
 * Класс для работы с таблицей баллов пользователей
 */
class UserPoints extends Common
{
    protected $table = 'user_points';
    protected $fields = [
        'id',
        'user_id',
        'value'
    ];

}