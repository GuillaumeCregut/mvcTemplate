<?php

namespace Editiel98\Forms\Fields\Date;

class MonthField extends AbstractDateField
{
    protected string $typeOf = self::TYPE_STRING;

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'month';
    }
}
