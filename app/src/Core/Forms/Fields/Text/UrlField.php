<?php

namespace Editiel98\Forms\Fields\Text;

class UrlField extends AbstractTextField
{
    protected string $typeOf = self::TYPE_URL;

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, $allowBlank);
        $this->type = 'url';
    }
}
