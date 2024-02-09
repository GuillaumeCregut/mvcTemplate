<?php

namespace Editiel98\Forms\Fields;

use Editiel98\Forms\Fields\AbstractField;

class CheckBoxField extends AbstractField
{
    private string $value = '';
    private string $typeOf = self::TYPE_BOOL;
    private bool $checked;

    public function __construct(string $name, string $id, string $typeOf, ?bool $required = true, ?bool $checked = false)
    {
        parent::__construct($name, $id, $required);
        $this->typeOf = $typeOf;
        $this->checked = false;
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
        if ($this->checked) {
            $input .= ' checked';
        }
        if ($this->value !== '') {
            $input .= ' value="' . $this->value . '"';
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

    /**
     * Get the value of checked
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * Set the value of checked
     */
    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
