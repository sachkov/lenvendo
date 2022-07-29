<?php
namespace App\Controller;
use App\Http;


class FirstPage extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {

        $this->response->setContent('<br><br>');

        return $this->response;
    }
}