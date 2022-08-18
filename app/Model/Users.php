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

    /**
     * Get User by login
     * @param string user_login
     * @return array|false array of user data or false if user does't exist
     */
    public function getUserByLogin(string $login)
    {
        $db = $this->getDb();

        $sql = "
            SELECT * FROM `users`
            WHERE login = ?
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);

        $stmt->bindValue(1, strval($login));
        $resultSet = $stmt->executeQuery();
        $res = $resultSet->fetchAssociative();

        return $res;
    }

    /**
     * Adding new user.
     * @param string $login
     * @param string $password
     * @return array|false array of user data or false if error
     */
    public function addNew(string $login, string $password)
    {
        $db = $this->getDb();

        $sqlGet = "
            SELECT * FROM `users`
            WHERE login = ?
            LIMIT 1
        ";
        $stmtGet = $db->prepare($sqlGet);
        $stmtGet->bindValue(1, strval($login));

        $db->beginTransaction();

        try{
            // Получаем пользователя
            $resultSetGetBefore = $stmtGet->executeQuery();
            $res = $resultSetGetBefore->fetchAssociative();

            if($res) throw new \Exception('User \"'.$res['login'].'\" already exist');
            
            $db->insert(
                'users', 
                [
                    'login' => strval($login),
                    'password'=> password_hash(strval($password), PASSWORD_DEFAULT)
                ]
            );

            $resultSetAfter = $stmtGet->executeQuery();
            $res = $resultSetAfter->fetchAssociative();

            if(!$res) throw new \Exception('Error of adding new user \"'.$res['login'].'\".');

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }

        return $res;
    }

}