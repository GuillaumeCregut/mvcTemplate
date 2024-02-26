<?php

namespace Editiel98\Kernel\Attribute\Constraints;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NotBlankConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "Ce champ ne peut être vide";
    }

    public function isOK(mixed $value): bool
    {
        //a modifier
        if (!isset($value)) {
            return false;
        }
        if (is_bool($value)) {
            return true;
        }
        if (is_int($value) || is_float($value)) {
            return true;
        }
        if (empty($value)) {
            return false;
        }
        return true;
    }
}
