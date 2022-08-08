<?php
namespace App\Model;

use App\Model;
use Doctrine\DBAL;

/**
 * Класс для работы с таблицей призов
 */
class Users extends Model\Common
{
    protected $table = 'users';
    protected $fields = [
        'id',
        'login',
        'password'
    ];

}