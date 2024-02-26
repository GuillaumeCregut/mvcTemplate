<?php

namespace Editiel98\Kernel\Attribute\Constraints;

interface ConstraintInterface
{
    public function getMessage(): string;
    public function isOK(mixed $value): bool;
}
