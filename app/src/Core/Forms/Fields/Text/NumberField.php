<?php

namespace Editiel98\Forms\Fields\Text;

class NumberField extends AbstractTextField
{
    protected string $typeOf = 'number';

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'number';
    }
}
