<?php

namespace Editiel98\Kernel\Entity;

use Exception;

trait TraitEntity
{
    public function createFromData(array $datas)
    {
        var_dump($datas);
        try{
        foreach ($datas as $name => $field) {
            if (property_exists($this::class, $name)) {
                $this->$name = $field;
            } else {
                echo "Passe pas $name";
            }
        }
    } catch(Exception $e) {
        var_dump($e->getMessage());
    }
    }
}
