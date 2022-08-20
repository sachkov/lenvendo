<?php
namespace Tests\Service\Tabor;

use PHPUnit\Framework\TestCase;
use App;

class Sum extends TestCase
{
    public function test_getSum()
    {
        $container = new App\Container;
        $obj = $container->get(App\Service\Tabor\Sum::class);
        $this->assertInstanceOf(App\Service\Tabor\Sum::class, $obj);

        $res1 = $obj->getSum(1,0);
        $res2 = $obj->getSum(1,0);
        $this->assertNotEquals($res1, $res2);

        $res3 = $obj->getSum(1,1);
        $res4 = $obj->getSum(1,1);
        $this->assertNotEquals($res3, $res2);
        $this->assertEquals($res3, $res4);
    }

}