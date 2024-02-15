<?php

namespace Editiel98\Forms\Fields;

class TextAreaField extends AbstractField
{
    protected string $typeOf = self::TYPE_STRING;
    private string $value;

    public function __construct(string $name, string $id, ?bool $required = true, ?string $value=null)
    {
        parent::__construct($name, $id, $required);
        $this->value = $value;
    }

    public function display(): string
    {
        $displayError = $this->error ? 'error_field' : '';
        $label = '<label for="' . $this->id . '"';
        if ($this->label['class'] !== '') {
            $label .= ' class="' . $this->label['class'] . ' ' . $displayError . '"';
        } elseif ($this->error) {
            $label .= 'class="' . $displayError . '"';
        }
        $label .= '>' . $this->label['value'];
        $textarea = '<textarea name="' . $this->name . '" id="' . $this->id . '"';
        if ($this->class !== '') {
            $textarea .= ' class="' . $this->class . '"';
        }
        $textarea .= '>' . $this->value . '</textarea>';
        return $label . $textarea . '</label>';
    }

    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}
