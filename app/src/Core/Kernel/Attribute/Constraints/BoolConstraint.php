<?php

namespace Editiel98\Kernel\Attribute\Constraints;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class BoolConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "Cette valeur doit être un booléen";
    }

    public function isOK(mixed $value): bool
    {
        if (!is_bool($value) && ($value !== 'on')) {
            return false;
        }
        return true;
    }
}
