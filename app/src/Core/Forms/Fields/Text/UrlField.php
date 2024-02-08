<?php

namespace Editiel98\Forms\Fields\Text;

class UrlField extends AbstractTextField
{
    protected string $typeOf = 'string';

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'url';
    }
}
