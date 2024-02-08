<?php

namespace Editiel98\Forms\Fields\Date;

class DateTimeField extends AbstractDateField
{
    protected string $typeOf = 'string';

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'datetime-local';
    }
}
