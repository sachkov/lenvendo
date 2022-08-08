<?php

namespace Tests\Service\PrizeAction;

use PHPUnit\Framework\TestCase;
use App;

class Common extends TestCase
{
    public function test_getActions()
    {
        $container = new App\Container;
        $obj = $container->get(App\Service\PrizeAction\Common::class);
        $this->assertInstanceOf(App\Service\PrizeAction\Common::class, $obj);
    }

    public function test_getActionByCode()
    {
        $container = new App\Container;
        $obj = $container->get(App\Service\PrizeAction\Common::class);
        $this->assertInstanceOf(App\Service\PrizeAction\Common::class, $obj);
    }

    public function test_setActionLog()
    {
        $container = new App\Container;
        $obj = $container->get(App\Service\PrizeAction\Common::class);
        $this->assertInstanceOf(App\Service\PrizeAction\Common::class, $obj);
    }
}