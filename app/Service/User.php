<?php
namespace App\Service;

use App\Model;

class User
{  
    protected $usersModel;

    public function __construct(Model\Users $users)
    {
        $this->usersModel = $users;
    }

    public function login($request)
    {
        if(isset($request['loguot'])){
            unset($_SESSION['user']);
            header('Location: /', true, 302); die;
            return [];
        }

        if(!isset($request['login']) || !isset($request['password'])){
            return [
                'login'=>$request['login'],
                'error'=>'login or password is not set.'
            ];
        }

        if(strlen($request['login']) < 3){
            return [
                'login'=>$request['login'],
                'error'=>"login can't be less whan 3 symbols."
            ];
        }

        if(strlen($request['password']) < 3){
            return [
                'login'=>$request['login'],
                'error'=>"password can't be less whan 3 symbols."
            ];
        }

        $user = $this->usersModel->getUserByLogin($request['login']);

        
        if($user){
            // password check
            if(!password_verify(strval($request['password']), $user['password'])){
                unset($_SESSION['user']);
                return [
                    'login'=>$request['login'],
                    'error'=>'wrong password'
                ];
            }
        }else{
            // create new user if does't exist
            $user = $this->usersModel->addNew($request['login'], $request['password']);
            if(!$user){
                return [
                    'login'=>$request['login'],
                    'error'=>'creating user error.'
                ];
            }
        }

        $_SESSION['user'] = $user;
        return $user;
        // $backurl = '/';
        // if(isset($request['backurl']) && $request['backurl']){
        //     $backurl = strval($request['backurl']);
        // }
        // header('Location: '.$backurl, true, 301); die;
    }
}