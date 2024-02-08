<?php

namespace Editiel98\Forms\Fields\Multiple;

use Editiel98\Forms\Fields\AbstractField;

class SelectField extends AbstractField
{
    private string $typeOf = "string";
    private array $options = [];
    private string $value = "";

    public function __construct(string $name, string $id, ?string $value='')
    {
        parent::__construct($name, $id);
        $this->value = $value;
    }

    public function display(): string
    {
        $label = '<label for="' . $this->id . '"';
        if ($this->label['class'] !== '') {
            $label .= ' class="' . $this->label['class'] . '"';
        }
        $label.='>';
        $label .= $this->label['value'];
        $select = '<select name="' . $this->name . '" id="'.$this->id.'"';
        if ($this->class !== '') {
            $select .= ' class="' . $this->class . '"';
        }
        $select .='>';
        foreach ($this->options as $key => $value) {
            $select .= '<option value="' . $key . '"';
            if ($this->value === $key) {
                $select .= ' selected';
            }
            $select .= '>' . $value . '</option>';
        }
        $select .= '</select>';
        return $label .$select.'</label>';
    }

    public function getTypeOf(): string
    {
        return $this->typeOf;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function addOption(string $display, string $value): self
    {
        $this->options[$value] = $display;
        return $this;
    }

    public function removeOption(string $id): self
    {
        unset($this->options[$id]);
        return $this;
    }
}
