<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\Events\EventManager;
use Editiel98\Kernel\Events\EventSubcriber;


class EventSubscriberTest extends TestCase
{
    public function testSubscribeEvent(): void
    {
        EventSubcriber::subscribe('test', function(){});
        $eventManager=EventManager::create();
        $this->assertTrue($eventManager->hasListener('test'));
    }
}