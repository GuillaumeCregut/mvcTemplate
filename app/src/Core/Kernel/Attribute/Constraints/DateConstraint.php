<?php

namespace Editiel98\Kernel\Attribute\Constraints;

class DateConstraint extends AbstractConstraints implements ConstraintInterface
{
    public function __construct()
    {
        $this->message = "Cette valeur doit être au format YYYY-MM-DD";
    }
    public function isOK(mixed $value): bool
    {
        if (null === $value) {
            return true;
        }
        $flag = "/\d{4}-(0[1-9]|1[1,2])-(0[1-9]|[12][0-9]|3[01])$/";
        $result = filter_var(
            $value,
            FILTER_VALIDATE_REGEXP,
            array("options" => array("regexp" => $flag), "flags" => FILTER_NULL_ON_FAILURE)
        );
        if (null === $result) {
            return false;
        }
        try {
            $test = new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
