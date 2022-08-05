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

        if($this->request->get('path') != '/'){
            header('Location: /', true, 307); die;
        }
    }

}