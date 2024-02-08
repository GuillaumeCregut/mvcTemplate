<?php

namespace Editiel98\Forms\Fields;

use Editiel98\Forms\Fields\AbstractField;

class CheckBoxField extends AbstractField
{
    private bool $value = false;
    private string $typeOf = 'boolean';

    public function __construct(string $name, string $id)
    {
        parent::__construct($name, $id);
    }

    /**
     * return HTML code for field
     * @return string
     */
    public function display(): string
    {
        $label = '<label for="' . $this->id . '"';
        if (strlen($this->label['class']) !== 0) {
            $label .= ' class="' . $this->label['class'] . '"';
        }
        $label .= '>' . $this->label['value'];

        $input = '<input type="checkbox" name="' . $this->name . '"';
        if ($this->id !== '') {
            $input .= ' id="' . $this->id . '"';
        }
        if ($this->class !== '') {
            $input .= ' class="' . $this->class . '"';
        }
        if ($this->value) {
            $input .= ' checked';
        }
        if (!empty($this->dataset)) {
            foreach ($this->dataset as $key => $value) {
                $input .= ' ' . $key . '="' . $value . '"';
            }
        }
        $input .= '>';
        return $label . $input . '</label>';
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

    /**
     * Get the value of typeOf
     */
    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}
