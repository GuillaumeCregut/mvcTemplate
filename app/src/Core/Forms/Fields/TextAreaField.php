<?php
namespace Editiel98\Forms\Fields;

class TextAreaField extends AbstractField
{
    protected string $typeOf='string';
    private string $value;

    public function __construct(string $name, string $id, ?string $value)
    {
        parent::__construct($name,$id);
        $this->value=$value;
    }

    public function display(): string
    {
        $label='<label for="'.$this->id.'"';
        if($this->label['class']!=='') {
            $label.=' class="'.$this->label['class'].'"';
        }
        $label.='>'.$this->label['value'];
        $textarea='<textarea name="'.$this->name.'" id="'.$this->id.'"';
        if($this->class!=='') {
            $textarea.=' class="'.$this->class.'"';
        }
        $textarea.='>'.$this->value.'</textarea>';
        return $label . $textarea . '</label>';
    }

    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}