<?php

namespace Editiel98\Forms\Fields;

use Editiel98\Forms\TraitTypeOf;

abstract class AbstractField
{
    use TraitTypeOf;
    protected bool $required;
    protected string $name = '';
    protected string $class = '';
    protected string $id = '';
    protected array $dataset = [];
    protected array $label = ['class' => '', 'value' => ''];
    protected string $placeholder = '';
    

    public function __construct(string $name, string $id, bool $required)
    {
        $this->name = $name;
        $this->id = $id;
        $this->required=$required;
    }

    abstract public function getTypeOf(): string;

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of class
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Set the value of class
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of dataset
     */
    public function getDataset(): array
    {
        return $this->dataset;
    }

    /**
     * Set the value of dataset
     */
    public function AddDataset(array $dataset): self
    {
        $this->dataset[] = $dataset;

        return $this;
    }

    abstract protected function display(): string;

    /**
     * Get the value of label
     */
    public function getLabel(): array
    {
        return $this->label;
    }

    /**
     * Set the value of label
     */
    public function setLabel(array $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of placeholder
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * Set the value of placeholder
     */
    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get the value of required
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
