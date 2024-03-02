<?php

namespace Editiel98\Kernel\Attribute\Constraints;

class FloatConstraint extends AbstractConstraints implements ConstraintInterface
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
        if (is_float($value)) {
            var_dump('isfloat');
            return true;
        }
        $floated = (float) $value;
        return (string)$floated === $value;
    }
}
