<?php

namespace Editiel98\Chore;

/**
 * Message system for application
 */
class Emitter
{

    private static $instance;
    private array $listeners = [];

    const DATABASE_ERROR = 'database.error';
    const DATABASE_WARNING = 'database.warning';
    const MAIL_ERROR = 'mail.error';


    /**
     * Get Emitter instance (singleton)
     *
     * @return Emitter
     */
    public static function getInstance(): Emitter
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Emit an event to be listen
     *
     * @param string $event
     * @param [type] ...$args
     * @return void
     */
    public function emit(string $event, ...$args)
    {
        if ($this->hasListener($event)) {
            foreach ($this->listeners[$event] as $listener) {
                call_user_func_array($listener, $args);
            }
        }
    }

    /**
     * Listen an event
     *
     * @param string $event event name
     * @param callable $action
     * @return void
     */
    public function on(string $event, callable $action)
    {
        if (!$this->hasListener($event)) {
            $this->listeners[$event] = [];
        }
        $this->listeners[$event][] = $action;
    }

    private function hasListener(string $event): bool
    {
        return array_key_exists($event, $this->listeners);
    }
}
