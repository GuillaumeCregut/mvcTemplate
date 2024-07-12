<?php

namespace Editiel98\Kernel\Events;

interface EventSubscriberInterface
{
    public static function subscribe(string $event, callable $callback): void;
}
