<?php

namespace Editiel98\Forms\Fields;

class HiddenField extends AbstractField
{
    private string $typeOf = self::TYPE_STRING;
    private string $value;

    public function __construct(string $name, string $id, string $value, string $typeOf, ?bool $required = true)
    {
        parent::__construct($name, $id, $required);
        $this->typeOf = $typeOf;
        $this->value = $value;
    }

    /**
     * http://framework:8080/test
     * @return string
     */
    public function display(): string
    {
        $input = '<input type=hidden name="' . $this->name . '" value="' . $this->value . '"';
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

    /**
     * Get the value of value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the value of value
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
