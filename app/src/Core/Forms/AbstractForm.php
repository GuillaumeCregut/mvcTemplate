<?php

namespace Editiel98\Forms;

use Editiel98\Forms\Fields\AbstractField;

abstract class AbstractForm
{
    protected string $token = '';
    protected string $method = '';
    protected string $action = '';
    protected array $fields = [];
    protected string $class = '';
    protected string $id = "";
    protected array $dataset = [];
    protected array $datas = [];

    public function __construct(string $token, string $method, string $action)
    {
        $this->action = $action;
        $this->method = $method;
        $this->token = $token;
    }

    protected function addField(string $name, AbstractField $field): self
    {
        $this->fields[$name] = $field;
        return $this;
    }

    public function getField(string $name): AbstractField
    {
        return $this->fields[$name];
    }

    /**
     * Return HTML Code form form with token
     * @return string
     */
    public function startForm(): string
    {
        $form = '<form method="' . $this->method . '"';
        if (strlen($this->action) === 0) {
            $form .= ' action=""';
        } else {
            $form .= ' action="' . $this->action . '"';
        }
        if ($this->id !== '') {
            $form .= ' id="' . $this->id . '"';
        }
        if ($this->class !== '') {
            $form .= ' class="' . $this->class . '"';
        }
        if (!empty($this->dataset)) {
            foreach ($this->dataset as $key => $value) {
                $form .= ' ' . $key . '="' . $value . '"';
            }
        }
        $form .= '>';
        $form .= '<input type="hidden" name="token" value="' . $this->token . '">';
        return $form;
    }

    /**
     * Close HTML Code for form
     * @return string
     */
    public function endForm(): string
    {
        return "</form>";
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
    public function addDataset(array $dataset): self
    {
        $this->dataset[] = $dataset;

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
}
