<?php

namespace Editiel98\Kernel\Attribute\Constraints;

class IntConstraint extends AbstractConstraints implements ConstraintInterface
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
        if (is_int($value)) {
            return true;
        }
        $filter = FILTER_VALIDATE_INT;
        $flag = FILTER_NULL_ON_FAILURE;
        $result = filter_var($value, $filter, $flag);
        if (null === $result) {
            return false;
        }
        return true;
    }
}
