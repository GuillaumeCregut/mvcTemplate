<?php

namespace Editiel98\Kernel\Events;

use Editiel98\Psr\ListenerProviderInterface;
use Editiel98\Psr\StoppableEventInterface;

class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var mixed[]
     */
    private $listeners = [];

    /**
     * @var ListenerProvider
     */
    private static $instance;

    public static function getInstance(): ListenerProvider
    {
        if (is_null(self::$instance)) {
            self::$instance = new ListenerProvider();
        }
        return self::$instance;
    }

    /**
     * @return mixed[]
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventType = get_class($event);
        $listeners = [];
        if (array_key_exists($eventType, $this->listeners)) {
            $listenersArray = $this->listeners[$eventType];
            usort($listenersArray, function ($a, $b) {
                return $b['priority'] - $a['priority'];
            });
            foreach ($listenersArray as $listener) {
                $listeners[] = $listener['callback'];
            }
        }

        return $listeners;
    }

    /**
     * @return mixed[]
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    public function addListener(string $eventType, ListenerInterface $callback, int $priority = 0): self
    {
        if (!($callback instanceof ListenerInterface)) {
            throw new EventException('Listener must implement ListenerInterface');
        }
        if (!class_implements($eventType)) {
            throw new EventException('Event type must implement StoppableEventInterface');
        }
        if (!(in_array(StoppableEventInterface::class, class_implements($eventType), true))) {
            throw new EventException('Event is not supported');
        }
        $listener = [
            'callback' => $callback,
            'priority' => $priority
        ];
        $this->listeners[$eventType][] = $listener;
        return $this;
    }

    public function clearListeners(string $eventType): void
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }
    }
}
