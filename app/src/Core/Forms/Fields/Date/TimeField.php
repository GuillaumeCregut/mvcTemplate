<?php

namespace Editiel98\Forms\Fields\Date;

class TimeField extends AbstractDateField
{
    protected string $typeOf = self::TYPE_TIME;

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'time';
    }
}
