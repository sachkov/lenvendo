<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App;

class Router extends TestCase
{
    public function test_handle()
    {
        $obj = new App\Router;
        $this->assertEquals(get_class($obj),"App\Router");

        $container = new App\Container;
        $requst = $container->get(App\Http\Request::class);

        $response = $obj->handle($requst);
        $this->assertInstanceOf(\App\Http\Response::class, $response);
    }
}