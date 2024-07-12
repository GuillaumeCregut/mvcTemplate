<?php

namespace Editiel98\Kernel\Events;

class EventSubcriber implements EventSubscriberInterface
{
    public static function subscribe(string $event, callable $callback): void
    {
        $eventManager = EventManager::create();
        $eventManager->addListener($event, $callback);
    }
}
