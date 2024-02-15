<?php

namespace Editiel98\Forms\Fields\Multiple;

use Editiel98\Forms\Fields\AbstractField;

class SelectField extends AbstractField
{
    private string $typeOf = self::TYPE_STRING;
    /**
     * @var array<mixed>
     */
    private array $options = [];
    private string $value = "";

    public function __construct(string $name, string $id, string $typeOf, ?bool $required = true, ?string $value = '')
    {
        parent::__construct($name, $id, $required);
        $this->value = $value;
        $this->typeOf = $typeOf;
    }

    /**
     * Return HTML Code for field
     * @return string
     */
    public function display(): string
    {
        $displayError = $this->error ? 'error_field' : '';
        $label = '<label for="' . $this->id . '"';
        if ($this->label['class'] !== '') {
            $label .= ' class="' . $this->label['class'] . ' ' . $displayError . '"';
        } elseif ($this->error) {
            $label .= 'class="' . $displayError . '"';
        }
        $label .= '>';
        $label .= $this->label['value'];
        $select = '<select name="' . $this->name . '" id="' . $this->id . '"';
        if ($this->class !== '') {
            $select .= ' class="' . $this->class . '"';
        }
        $select .= '>';
        foreach ($this->options as $key => $value) {
            $select .= '<option value="' . $key . '"';
            if ($this->value === $key) {
                $select .= ' selected';
            }
            $select .= '>' . $value . '</option>';
        }
        $select .= '</select>';
        return $label . $select . '</label>';
    }

    /**
     * @return string
     */
    public function getTypeOf(): string
    {
        return $this->typeOf;
    }

    /**
     * @param array<mixed> $options
     * ["value"=>"display",]
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $display
     * @param string $value
     * 
     * @return self
     */
    public function addOption(string $display, string $value): self
    {
        $this->options[$value] = $display;
        return $this;
    }

    /**
     * @param string $id
     * 
     * @return self
     */
    public function removeOption(string $id): self
    {
        unset($this->options[$id]);
        return $this;
    }
}
