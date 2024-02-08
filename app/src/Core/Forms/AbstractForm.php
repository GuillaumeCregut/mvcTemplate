<?php

namespace Editiel98\Forms;

use Editiel98\Chore\CSRFCheck;
use Editiel98\Factory;
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
    protected bool $multipart;
    private CSRFCheck $csrfCheck;

    public function __construct(string $method, string $action, ?bool $multipart = false)
    {
        $this->action = $action;
        $this->method = $method;
        $this->multipart = $multipart;
        $session=Factory::getSession();
        $this->csrfCheck=new CSRFCheck($session);
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
        $this->token=$this->csrfCheck->createToken();
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
        if ($this->multipart) {
            $form .= ' enctype="multipart/form-data"';
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

    public function getFields(): array
    {
        return $this->fields;
    }

    public function checkDatas(array $datas): bool
    {
        if(empty($datas))
            return false;
        if(!isset($datas['token'])){
            return false;
        }
        $token=$datas['token'];
        var_dump($this->csrfCheck->checkToken($token)); //Works fine
        //     return false;
        $this->testDatas($datas);
        return false;
    }

    private function testDatas(array $datas)
    {
        echo "<p>Forms infos</p>";
        foreach($this->getFields() as $field){
            var_dump($field->getName());
        }
       echo '<p>$_POST</p>';
       foreach($datas as $key=>$data) {
            var_dump($key,$data);
       }
    }
}
