<?php

namespace Editiel98\Psr;

interface StoppableEventInterface
{
    public function isPropagationStopped(): bool;
}
