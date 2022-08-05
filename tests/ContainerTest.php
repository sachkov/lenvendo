<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App;

class Container extends TestCase
{
    public function test_set()
    {
        $obj = new App\Container;
        $this->assertEquals(get_class($obj),"App\Container");

        $obj->set(['stdClass::class'=>\stdClass::class]);
        $this->assertTrue($obj->has('stdClass::class'));
        $this->assertFalse($obj->has('\stdClass::class'));
        $this->assertFalse($obj->has('stdClass'));
    }

    public function test_add()
    {
        $obj = new App\Container;
        $this->assertEquals(get_class($obj),"App\Container");

        $obj->set(['stdClass::class'=>\stdClass::class]);
        $this->assertTrue($obj->has('stdClass::class'));
        $this->assertFalse($obj->has('\stdClass::class'));
        $this->assertFalse($obj->has('stdClass'));
    }

    public function test_has()
    {
        $obj = new App\Container;
        $this->assertEquals(get_class($obj),"App\Container");

        $this->assertFalse($obj->has('stdClass::class'));
        $this->assertFalse($obj->has('\stdClass::class'));
        $this->assertFalse($obj->has('stdClass'));

        $obj->set(['stdClass::class'=>\stdClass::class]);
        $this->assertTrue($obj->has('stdClass::class'));
        $this->assertFalse($obj->has('\stdClass::class'));
        $this->assertFalse($obj->has('stdClass'));
    }

    public function test_get()
    {
        $obj = new App\Container;
        $this->assertEquals(get_class($obj),"App\Container");

        $res = $obj->get(\App\Router::class);
        $this->assertInstanceOf(\App\Router::class, $res);
        $this->assertEquals(get_class($res),"App\Router");

        $res = $obj->get(\App\Http\Request::class);
        $this->assertInstanceOf(\App\Http\Request::class, $res);
        $this->assertEquals(get_class($res),"App\Http\Request");
    }
}