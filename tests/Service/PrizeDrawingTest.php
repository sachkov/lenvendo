<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use App;

class PrizeDrawing extends TestCase
{
    public function test_getLastPrize()
    {
        $container = new App\Container;
        $obj = $container->get(App\Service\PrizeDrawing::class);
        $this->assertInstanceOf(App\Service\PrizeDrawing::class, $obj);

        $userId = 0;
        $res = $obj->getLastPrize($userId);
        $this->assertFalse($res);

        $UserModel = $container->get(App\Model\Users::class);
        $lastUser = $UserModel->getLast();

        $res = $obj->getLastPrize(($lastUser['id']+1));
        $this->assertFalse($res);

        $WinnersModel = $container->get(App\Model\PrizeDrawing\Winners::class);
        $winner = $WinnersModel->getLast();

        if(!$winner) return false;

        $res = $obj->getLastPrize($winner['user_id']);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('draw_id', $res);
    }

    public function getPrizeAction()
    {

    }
}