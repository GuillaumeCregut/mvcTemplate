<?php

namespace Editiel98\Kernel\Attribute\Constraints;

class DateTimeConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "La valeur doit être de type YYYY-MM-DDTHH:MM";
    }
    public function isOK(mixed $value): bool
    {
        if (null === $value) {
            return true;
        }
        $flag = "/\d{4}-(0[1-9]|1[1,2])-(0[1-9]|[12][0-9]|3[01])[A-Z](0[0-9]|1[0-9]|2[0-3]):([0-5])([0-9])/";
        $result = filter_var(
            $value,
            FILTER_VALIDATE_REGEXP,
            array("options" => array("regexp" => $flag), "flags" => FILTER_NULL_ON_FAILURE)
        );
        if (null === $result) {
            return false;
        }
        return true;
    }
}
