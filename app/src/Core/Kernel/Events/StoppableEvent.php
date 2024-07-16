<?php

namespace Editiel98\Kernel\Events;

use Editiel98\Psr\StoppableEventInterface;

class StoppableEvent implements StoppableEventInterface
{
    private bool $propagationStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}
