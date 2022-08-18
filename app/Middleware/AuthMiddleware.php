<?php
namespace App\Middleware;


class AuthMiddleware extends Common
{
    public function handle()
    {
        if (headers_sent()) return false;

        session_start();

        if(isset($_SESSION['user']) && $_SESSION['user']){
            $this->request->setUser($_SESSION['user']);
            return true;
        }
        //echo '<pre>';
        //print_r($_SESSION);die;

        // if user is not authtorized, but it is required.
        //header('Location: /auth', true, 307); die;
    }

}