<?php

use PHPUnit\Framework\TestCase;
use Editiel98\Kernel\Events\EventManager;
use Editiel98\Kernel\Events\EventSubscriber;


class EventSubscriberTest extends TestCase
{
    public function testSubscribeEvent(): void
    {
        EventSubscriber::subscribe('test', function(){});
        $eventManager=EventManager::create();
        $this->assertTrue($eventManager->hasListener('test'));
    }
}