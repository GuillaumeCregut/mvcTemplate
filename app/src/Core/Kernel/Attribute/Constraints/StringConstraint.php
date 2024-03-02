<?php

namespace Editiel98\Kernel\Attribute\Constraints;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class StringConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "Cette valeur doit être un texte";
    }

    public function isOK(mixed $value): bool
    {
        if (null === $value) {
            return true;
        }
        if (!is_string($value)) {
            return false;
        }
        return true;
    }
}
