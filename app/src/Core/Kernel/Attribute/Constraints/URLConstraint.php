<?php

namespace Editiel98\Kernel\Attribute\Constraints;

class URLConstraint extends AbstractConstraints implements ConstraintInterface
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
        $filter = FILTER_VALIDATE_URL;
        if ('' === $value) {
            return false;
        }
        $url = filter_var($value, FILTER_SANITIZE_URL, FILTER_NULL_ON_FAILURE);
        if (null === $url) {
            return false;
        }
        $result = filter_var($url, $filter, FILTER_FLAG_HOSTNAME);
        if (null === $result) {
            return false;
        }
        $regex = "/^http?(s)?(:\/\/)?(www.)?[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})(\.[a-zA-Z0-9]{2,})?((\/)?(\w+)?)+/";
        $result = filter_var(
            $value,
            FILTER_VALIDATE_REGEXP,
            array("options" => array("regexp" => $regex), "flags" => FILTER_NULL_ON_FAILURE)
        );
        if (null === $result) {
            return false;
        }
        return true;
    }
}
