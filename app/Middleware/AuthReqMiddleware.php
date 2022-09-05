<?php
namespace App\Middleware;


class AuthReqMiddleware extends Common
{
    public function handle()
    {
        if (headers_sent()) return false;

        if ($this->request->get('user')) return true;

        header('Location: /auth?backurl='.$this->request->get('path'), true, 307);
        die;
    }

}