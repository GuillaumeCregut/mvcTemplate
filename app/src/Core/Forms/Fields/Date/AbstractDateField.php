<?php

namespace Editiel98\Forms\Fields\Date;

use Editiel98\Forms\Fields\AbstractField;

abstract class AbstractDateField extends AbstractField
{
    protected string $value = "";
    protected string $typeOf;
    protected bool $allowBlank;
    protected string $type;
    protected string $min;
    protected string $max;
    protected string $step;

    public function __construct(string $name, string $id, ?bool $allowBlank = false)
    {
        parent::__construct($name, $id, !$allowBlank);
        $this->allowBlank = $allowBlank;
    }

    private function displayLabel(): string
    {
        $displayError = $this->error ? 'error_field' : '';
        $label = '<label for="' . $this->id . '"';
        if (strlen($this->label['class']) !== 0) {
            $label .= ' class="' . $this->label['class'] . ' ' . $displayError . '"';
        } elseif ($this->error) {
            $label .= 'class="' . $displayError . '"';
        }

        $label .= '>' . $this->label['value'];
        return $label;
    }

    /**
     * Return HTML Code for field
     * @return string
     */
    public function display(): string
    {
        $label = $this->displayLabel();
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
        if (isset($this->min)) {
            $input .= ' min="' . $this->min . '"';
        }
        if (isset($this->max)) {
            $input .= ' max="' . $this->max . '"';
        }
        if (isset($this->step)) {
            $input .= ' step="' . $this->step . '"';
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
     * Get the value of step
     */
    public function getStep(): string
    {
        return $this->step;
    }

    /**
     * Set the value of step
     */
    public function setStep(string $step): self
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get the value of allowBlank
     */
    public function isAllowBlank(): bool
    {
        return $this->allowBlank;
    }

    /**
     * Set the value of allowBlank
     */
    public function setAllowBlank(bool $allowBlank): self
    {
        $this->allowBlank = $allowBlank;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of min
     */
    public function getMin(): string
    {
        return $this->min;
    }

    /**
     * Set the value of min
     */
    public function setMin(string $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get the value of max
     */
    public function getMax(): string
    {
        return $this->max;
    }

    /**
     * Set the value of max
     */
    public function setMax(string $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get the value of step
     */

    /**
     * Get the value of typeOf
     */
    public function getTypeOf(): string
    {
        return $this->typeOf;
    }

    /**
     * Set the value of typeOf
     */
    public function setTypeOf(string $typeOf): self
    {
        $this->typeOf = $typeOf;

        return $this;
    }
}
