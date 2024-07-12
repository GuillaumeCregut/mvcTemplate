<?php

namespace Editiel98\Kernel\Events;

class EventManager
{
    /**
     * @var EventManager
     */
    private static $instance;
    /**
     * @var array<mixed>
     */
    private array $listeners = [];


    /**
     * Get Emitter instance (singleton)
     *
     * @return EventManager
     */
    public static function create(): EventManager
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addListener(string $event, callable $callback): void
    {
        if (!$this->hasListener($event)) {
            $this->listeners[$event] = [];
        }
        $this->listeners[$event][] = $callback;
    }

    public function hasListener(string $event): bool
    {
        return array_key_exists($event, $this->listeners);
    }

    /**
     * @param string $event
     * @param mixed ...$args
     *
     * @return void
     */
    public function emit(string $event, ...$args): void
    {
        if ($this->hasListener($event)) {
            foreach ($this->listeners[$event] as $listener) {
                call_user_func_array($listener, $args);
            }
        }
    }
}
