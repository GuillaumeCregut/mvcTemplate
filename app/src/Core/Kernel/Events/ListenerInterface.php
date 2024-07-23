<?php

namespace Editiel98\Kernel\Events;

interface ListenerInterface
{
    /**
     * @param mixed ...$args
     *
     * @return void
     */
    public function execute(...$args): void;
}
