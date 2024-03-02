<?php

namespace Editiel98\Forms;

use Editiel98\Forms\Fields\AbstractField;
use Editiel98\Kernel\CSRFCheck;
use Editiel98\Kernel\Entity\AbstractEntity;
use Editiel98\Kernel\Exception\WebInterfaceException;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Editiel98\Session;

abstract class AbstractForm
{
    use TraitTypeOf;

    /**
     * @var array<AbstractField>
     */
    protected array $fields = [];
    protected string $class = '';
    protected string $id = "";
    protected string $action = '';
    protected string $token = '';
    protected string $method = '';
    /**
     * @var class-string
     */
    protected string $entity;
    /**
     * @var array<mixed>
     */
    protected array $datas = [];
    protected bool $multipart;
    /**
     * @var array<mixed>
     */
    protected array $dataset = [];

    protected RequestHandler $handler;

    protected CSRFCheck $csrfCheck;

    public function __construct(string $method, string $action, ?bool $multipart = false)
    {
        $this->action = $action;
        $this->method = $method;
        $this->multipart = $multipart;
        $session = new Session();
        $this->csrfCheck = new CSRFCheck($session);
    }

    protected function addField(AbstractField $field): self
    {
        $name = $field->getName();
        $this->fields[$name] = $field;
        return $this;
    }

    public function getField(string $name): AbstractField
    {
        return $this->fields[$name];
    }

    /**
     * @return array<AbstractField>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<mixed>
     */
    public function getDataset(): array
    {
        return $this->dataset;
    }

    /**
     * @param array<mixed> $dataset
     *
     * @return self
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
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Close HTML Code for form
     * @return string
     */
    public function endForm(): string
    {
        return "</form>";
    }

    public function startForm(): string
    {
        $this->token = $this->csrfCheck->createToken();
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
     * Validate if datas from requestHandler are Entity constraints complient
     *
     * @return mixed[]
     */
    protected function validateData(): array | bool
    {
        $token = $this->handler->getCSRFToken();
        if (!$token) {
            throw new WebInterfaceException('No token provided');
        }
        if (!$this->csrfCheck->checkToken($token)) {
            throw new WebInterfaceException('Bad token provided');
        }
        $formValidator = new FormValidator();
        $datas = $this->handler->request->getAll();
        $result = $formValidator->validate($this->entity, $datas);
        if (!$result) {
            //TODO Here set fields with errors
            /*
                [fieldName=>[
                    'state'=>'missing',
                    'error_0'=>'This field is required',
                    'error_1'=>'This field is required',
                ]]
            */
            return $formValidator->getErrorInputs();
        }
        return $result;
    }

    public function handleRequest(RequestHandler $handler): void
    {
        $this->handler = $handler;
    }

    public function isSubmit(): bool
    {
        $allowMethod = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (!in_array($this->handler->getMethod(), $allowMethod)) {
            return false;
        }
        $posts = $this->handler->request->getAll();
        $files = $this->handler->files->getAll();
        $filesPresent = false;
        //TODO Check if files presents and if so, add them to the return

        return (!empty($posts));
    }

    protected function inflateEntity(): AbstractEntity
    {
        $hydrator = new Hydrator();
        $values = $this->handler->request->getAll();
        return $hydrator->hydrate($this->entity, $values);
    }
    abstract public function validate(): AbstractEntity | false;
}
