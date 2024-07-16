<?php

namespace Editiel98\Kernel\Events;

use Editiel98\Psr\ListenerProviderInterface;

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
            foreach ($this->listeners[$eventType] as $listener) {
                // TODO : Here order by priority
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

    public function addListener(string $eventType, callable $callback, int $priority = 0): self
    {
        // TODO : Check if eventType is event
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
