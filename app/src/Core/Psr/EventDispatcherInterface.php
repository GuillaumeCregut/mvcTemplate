<?php

namespace Editiel98\Psr;

interface EventDispatcherInterface
{
    public function dispatch(object $event): object;
}
