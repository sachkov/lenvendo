<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App;

class Application extends TestCase
{
    public function test_getRequest()
    {

        $obj = new App\Application;
        $this->assertEquals(get_class($obj),"App\Application");

        $res = $obj->getRequest();
        $this->assertInstanceOf(\App\Http\Request::class, $res);

        return $res;
    }

    /**
     * @depends test_getRequest
     */
    public function test_handle($request)
    {

        $obj = new App\Application;
        $this->assertEquals(get_class($obj),"App\Application");

        $res = $obj->handle($request);
        $this->assertInstanceOf(\App\Http\Response::class, $res);

        return $res;
    }

    /**
     * @depends test_getRequest
     * @depends test_handle
     */
    public function test_end($request, $responce)
    {

        $obj = new App\Application;
        $this->assertEquals(get_class($obj),"App\Application");

        $obj->end($request, $responce);
    }
}