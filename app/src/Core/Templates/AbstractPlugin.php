<?php

namespace Editiel98\Templates;

abstract class AbstractPlugin
{
    protected string $name;
    abstract public static function display(mixed $params, $smarty);
}
