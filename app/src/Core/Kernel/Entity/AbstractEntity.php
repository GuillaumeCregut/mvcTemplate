<?php

namespace Editiel98\Kernel\Entity;

use Exception;

/**
 * Abstract class Entity
 */
abstract class AbstractEntity
{
    use TraitEntity;

    protected int $id;
    /**
     * @var array<mixed>
     */
    protected array $datas = [];

    public function __construct()
    {
    }

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

    /**
     * @return array<mixed>
     */
    abstract public function getDatasForForm(): array;

    /**
     * @param mixed $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        $method = 'get' .  ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return null;
        }
    }

    /**
     * @param mixed $name
     * @param mixed $value
     *
     */
    public function __set($name, $value)
    {
        echo "Set $name a la valeur $value";
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else {
            throw new Exception('Method not found');
        }
    }
}
