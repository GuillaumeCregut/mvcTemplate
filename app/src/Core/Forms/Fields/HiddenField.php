<?php
namespace Editiel98\Forms\Fields;

class HiddenField extends AbstractField
{
    private string $typeOf='';
    private string $value;

    public function __construct(string $name,string $id, string $typeOf)
    {
        parent::__construct($name,$id);
        $this->typeOf=$typeOf;
    }

    public function display(): string
    {
        $input = '<input type=hidden name="'.$this->name.'" value"'.$this->value.'"';
        if($this->id!=='') {
            'id="'.$this->id.'"';
        }
        $input .= '>';
        return $input;
    }

    public function getTypeOf(): string
    {
        return $this->typeOf;
    }
}