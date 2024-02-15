<?php

namespace Editiel98\Forms;

use Editiel98\Factory;
use Editiel98\Forms\Fields\AbstractField;
use Editiel98\Kernel\CSRFCheck;
use Error;
use Exception;

abstract class AbstractMiniForm
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
     * @var array<mixed>
     */
    protected array $datas = [];
    protected bool $multipart;
    /**
     * @var array<mixed>
     */
    protected array $dataset = [];

    protected CSRFCheck $csrfCheck;

    public function __construct(string $method, string $action, ?bool $multipart = false)
    {
        $this->action = $action;
        $this->method = $method;
        $this->multipart = $multipart;
        $session = Factory::getSession();
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
     * @param mixed $value
     * @param int|null $filter
     * @param mixed|null $flag
     *
     * @return mixed return filtered value or null if not ok
     */
    protected function filterValue(mixed $value, ?int $filter = null, mixed $flag = null): mixed
    {
        try {
            if (is_null($filter)) {
                return filter_var(
                    $value,
                    FILTER_VALIDATE_REGEXP,
                    array("options" => array("regexp" => $flag), "flags" => FILTER_NULL_ON_FAILURE)
                );
            }
            if (is_null($flag)) {
                return filter_var($value, $filter);
            }
            return filter_var($value, $filter, $flag);
        } catch (Exception $e) {
            throw new Exception('Error in filter settings');
        } catch (Error $e) {
            throw new Exception('Error in filter settings');
        }
    }

    /**
     * @param string $typeOf
     * @param string $value
     * @return array<mixed>
     */
    protected function setFilter(string $typeOf, string $value): array|false
    {
        $flag = null;
        switch ($typeOf) {
            case self::TYPE_BOOL:
                $filter = FILTER_VALIDATE_BOOL;
                $flag = FILTER_NULL_ON_FAILURE;
                break;
            case self::TYPE_NUMBER:
                $filter = FILTER_VALIDATE_INT;
                $flag = FILTER_NULL_ON_FAILURE;
                break;
            case self::TYPE_STRING:
                if ($value === '') {
                    return false;
                }
                $filter = FILTER_SANITIZE_SPECIAL_CHARS;
                break;
            case self::TYPE_DATETIME:
                $filter = null;
                $flag = "/\d{4}-(0[1-9]|1[1,2])-(0[1-9]|[12][0-9]|3[01])[A-Z](0[0-9]|1[0-9]|2[0-3]):([0-5])([0-9])/";
                break;
            case self::TYPE_DATE:
                $filter = null;
                $flag = "/\d{4}-(0[1-9]|1[1,2])-(0[1-9]|[12][0-9]|3[01])/";
                break;
            case self::TYPE_URL:
                $filter = FILTER_VALIDATE_URL;
                break;
            case self::TYPE_FLOAT:
                $filter = FILTER_VALIDATE_FLOAT;
                $flag = FILTER_NULL_ON_FAILURE;
                break;
            default:
                $filter = null;
        }
        return array($filter, $flag);
    }
}
