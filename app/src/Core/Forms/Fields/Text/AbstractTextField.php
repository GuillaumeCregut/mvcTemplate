<?php

namespace Editiel98\Forms\Fields\Text;

use Editiel98\Forms\Fields\AbstractField;

abstract class AbstractTextField extends AbstractField
{
    protected string $value = "";
    protected string $typeOf;
    protected bool $allowBlank;
    protected string $type;

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id);
        $this->allowBlank = $allowBlank;
    }

    public function display(): string
    {
        $label = '<label for="' . $this->id . '"';
        if (strlen($this->label['class']) !== 0) {
            $label .= ' class="' . $this->label['class'] . '"';
        }
        $label .= '>' . $this->label['value'];

        $input = '<input type="' . $this->type . '" name="' . $this->name . '"';
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
        if ($this->placeholder != '') {
            $input .= ' placeholder="' . $this->placeholder . '"';
        }
        if (!$this->allowBlank) {
            $input .= ' required';
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
