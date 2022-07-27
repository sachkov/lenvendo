<?php
namespace App\Middleware;


class AuthMiddleware extends Common
{
    public function handle()
    {
        session_start();

        if(isset($_SESSION['user'])){
            $this->request->setUser($_SESSION['user']);
        }
    }

}