<?php

namespace Editiel98\Templates;

abstract class AbstractPlugin
{
    protected string $name;
    abstract static public function display(mixed $params,$smarty);
}