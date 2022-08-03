<?php
namespace App\Service\PrizeAction;

interface ActionHandlerInterface
{
    public function handleAction(array $action, array $prize);
}