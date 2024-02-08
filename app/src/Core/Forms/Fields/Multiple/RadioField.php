<?php

namespace Editiel98\Forms\Fields\Multiple;

use Editiel98\Forms\Fields\AbstractField;

class RadioField extends AbstractField
{
    private string $value = "";
    private string $typeOf = 'string';
    private bool $checked = false;

    public function __construct(string $name, string $id, string $value, ?bool $checked = false)
    {
        parent::__construct($name, $id);
        $this->checked = $checked;
        $this->value = $value;
    }

    public function display(): string
    {
        $label = '<label for="' . $this->id . '"';
        if (strlen($this->label['class']) !== 0) {
            $label .= ' class="' . $this->label['class'] . '"';
        }
        $label .= '>' . $this->label['value'];

        $input = '<input type="radio" name="' . $this->name . '"';
        if ($this->id !== '') {
            $input .= ' id="' . $this->id . '"';
        }
        if ($this->class !== '') {
            $input .= ' class="' . $this->class . '"';
        }
        if ($this->value != '') {
            $input .= ' value="' . $this->value . '"';
        }
        if (!empty($this->dataset)) {
            foreach ($this->dataset as $key => $value) {
                $input .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($this->checked) {
            $input .= ' checked';
        }
        $input .= '>';
        return $input . $label . '</label>';
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
