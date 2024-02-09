<?php

namespace Editiel98\Forms\Fields;

class HiddenField extends AbstractField
{
    private string $typeOf = self::TYPE_STRING;
    private string $value;

    public function __construct(string $name, string $id, string $typeOf, ?bool $required = true)
    {
        parent::__construct($name, $id, $required);
        $this->typeOf = $typeOf;
    }

    /**
     * http://framework:8080/test
     * @return string
     */
    public function display(): string
    {
        $input = '<input type=hidden name="' . $this->name . '" value"' . $this->value . '"';
        if ($this->id !== '') {
            'id="' . $this->id . '"';
        }
        $input .= '>';
        return $input;
    }

    /**
     * @return string
     */
    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}
