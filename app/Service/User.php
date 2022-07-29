<?php
namespace App\Service;


class User
{  
    public function login($request)
    {
        $db = \App\Application::$db->createQueryBuilder();

        if(!isset($request['login'])) return false;

        $user = $db
            ->from('users')
            ->where('login = ?')
            ->setParameter(0, strval($request['login']))
        ;



        return $user;
    }

}