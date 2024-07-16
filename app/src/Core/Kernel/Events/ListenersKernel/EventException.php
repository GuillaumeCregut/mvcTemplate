<?php

namespace Editiel98\Kernel\Events;

use Exception;
use Throwable;

class EventException extends Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": {$this->message}\n";
    }
}
