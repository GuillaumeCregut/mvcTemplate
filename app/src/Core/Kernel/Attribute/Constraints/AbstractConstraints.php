<?php

namespace Editiel98\Kernel\Attribute\Constraints;

abstract class AbstractConstraints
{
    protected string $message;

    public function getMessage(): string
    {
        return $this->message;
    }
}
