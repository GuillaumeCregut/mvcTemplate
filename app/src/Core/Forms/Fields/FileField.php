<?php

namespace Editiel98\Forms\Fields;

class FileField extends AbstractField
{
    protected string $typeOf = self::TYPE_FILE;
    /**
     * @var array<mixed>
     */
    private array $accept = [];

    /**
     * @param array<mixed> $accept
     */
    public function __construct(
        string $name,
        string $id,
        ?bool $required = true,
        ?array $accept = [],
        private ?bool $multiple = false
    ) {
        parent::__construct($name, $id, $required);
        $this->accept=$accept;
    }

    /**
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
        $label .= '>' . $this->label['value'];
        $input = '<input type="file" id="' . $this->id . '" name="' . $this->name . '"';
        if ($this->class !== '') {
            $input .= ' class="' . $this->class . '"';
        }
        if ($this->multiple) {
            $input .= ' multiple';
        }
        if (!empty($this->accept)) {
            $input .= ' accept="';
            foreach ($this->accept as $value) {
                $input .= $value . ',';
            }
            $input = substr($input, 0, strlen($input) - 1);
            $input .= '"';
        }
        $input .= '/>';
        return $label . $input . '</label>';
    }

    /**
     * @return string
     */
    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}
