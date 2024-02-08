<?php

namespace Editiel98\Forms\Fields;

class FileField extends AbstractField
{
    protected string $typeOf = 'file';

    public function __construct(
        string $name,
        string $id,
        private ?array $accept = [],
        private ?bool $multiple = false
    ) {
        parent::__construct($name,$id);
    }

    public function display(): string
    {
        $label='<label for="'.$this->id.'"';
        if($this->label['class']!=='') {
            $label.=' class="'.$this->label['class'].'"';
        }
        $label.='>'.$this->label['value'];
        $input='<input type="file" id="'. $this->name.'" name="'.$this->id.'"';
        if($this->class!=='') {
            $input.=' class="'.$this->class.'"';
        }
        if($this->multiple) {
            $input.=' multiple';
        }
        if(!empty($this->accept)) {
            $input.=' accept="';
            foreach($this->accept as $value) {
                $input.=$value .',';
            }
            $input=substr($input,0,strlen($input)-1);
            $input.='"';
        }
        $input .= '/>';
        return $label .$input. '</label>';
    }

    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}
