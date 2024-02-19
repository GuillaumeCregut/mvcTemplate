<?php

namespace Editiel98\Kernel\Exception;

use Exception;
use Throwable;

class WebInterfaceException extends Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
