<?php

namespace Editiel98\Forms;

class Pacificator
{
    public static function pacifyString(mixed $value): string
    {
        return htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
    }

    public static function pacifyInteger(mixed $value): int | null
    {
        $filter = FILTER_VALIDATE_INT;
        $flag = FILTER_NULL_ON_FAILURE;
        return filter_var($value, $filter, $flag);
    }

    public static function pacifyBool(mixed $value): bool | null
    {
        $filter = FILTER_VALIDATE_BOOLEAN;
        $flag = FILTER_NULL_ON_FAILURE;
        return filter_var($value, $filter, $flag);
    }

    public static function pacifyFloat(mixed $value): float | null
    {
        $filter = FILTER_VALIDATE_FLOAT;
        $flag = FILTER_NULL_ON_FAILURE;
        $result = filter_var($value, $filter, $flag);
        if ($result == '') {
            return null;
        }
        return (float) $result;
    }
}
