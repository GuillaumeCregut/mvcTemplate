<?php

namespace Editiel98\Kernel\Entity;

/**
 * Abstract class Entity
 */
abstract class AbstractEntity
{
    use TraitEntity;

    protected int $id;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
