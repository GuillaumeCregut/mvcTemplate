<?php

namespace Editiel98\Kernel\Entity;

use Exception;

trait TraitEntity
{

    /**
     * @param array<mixed> $datas
     * 
     * @return void
     */
    public function createFromData(array $datas): void
    {
        var_dump($datas);
        try {
            foreach ($datas as $name => $field) {
                if (property_exists($this::class, $name)) {
                    $this->$name = $field;
                } else {
                    echo "Passe pas $name";
                }
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
