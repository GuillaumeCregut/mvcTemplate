<?php

namespace Editiel98\Kernel\Attribute\Constraints;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CheckBoxConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "";
    }
    public function isOK(mixed $value): bool
    {
        return true;
    }
}
