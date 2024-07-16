<?php

namespace Editiel98\Kernel\Events;

use Editiel98\Psr\EventDispatcherInterface;
use Editiel98\Psr\ListenerProviderInterface;
use Editiel98\Psr\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private ListenerProviderInterface $listenerProvider;
    /**
     * @var EventDispatcher
     */
    private static $instance;

    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    public static function getInstance(ListenerProviderInterface $listenerProvider): EventDispatcher
    {
        if (null === self::$instance) {
            self::$instance = new EventDispatcher($listenerProvider);
        }
        return self::$instance;
    }
    public function dispatch(object $event): object
    {
        if (!($event instanceof StoppableEventInterface)) {
            throw new EventException('Not a Stoppable Event');
        }
        if ($event->isPropagationStopped()) {
            return $event;
        }
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            // @phpstan-ignore-next-line
            if ($event->isPropagationStopped()) {
                return $event;
            }
            $listener($event);
        }
        return $event;
    }
}
