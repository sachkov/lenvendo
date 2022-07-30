<?php
namespace App\Service;


class User
{  
    public function login($request)
    {
        $db = \App\Application::$db;

        if(isset($request['loguot'])){
            unset($_SESSION['user']);
            return [];
        }

        if(!isset($request['login']) || !isset($request['password'])) return [];

        $sql = "
            SELECT * FROM `users`
            WHERE login = ?
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, strval($request['login']));
        $resultSet = $stmt->executeQuery();
        $res = $resultSet->fetchAssociative();

        if(!$res){
            //create new user
            $db->insert(
                'users', 
                [
                    'login' => strval($request['login']),
                    'password'=> password_hash(strval($request['password']), PASSWORD_DEFAULT)
                ]
            );
            $resultSet = $stmt->executeQuery();
            $res = $resultSet->fetchAssociative();

            if(!$res) throw new \Exception('User creating error.');
            $_SESSION['user'] = $res;
            return $res;
        }

        // password check
        if(!password_verify(strval($request['password']), $res['password'])){
            unset($_SESSION['user']);
            return [
                'login'=>$request['login'],
                'error'=>'wrong password'
            ];
        }

        $_SESSION['user'] = $res;
        return $res;
    }

}