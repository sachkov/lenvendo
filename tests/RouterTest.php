<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App;

class Router extends TestCase
{
    public function test_handle()
    {
        $container = new App\Container;

        $respObj = $container->get(App\Http\Response::class);

        $obj = new App\Router($respObj);
        $this->assertInstanceOf(App\Router::class, $obj);

        $requst = $container->get(App\Http\Request::class);
        

        $response = $obj->handle($requst);
        $this->assertInstanceOf(\App\Http\Response::class, $response);
    }
}