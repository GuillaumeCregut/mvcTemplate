<?php

namespace App\Events;

use Editiel98\Kernel\Events\StoppableEvent;

class DemoEvent extends StoppableEvent
{
    //for demo purpose
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
