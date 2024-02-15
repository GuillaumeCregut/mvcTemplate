<?php

namespace Editiel98\Forms;

use Editiel98\Kernel\CSRFCheck;
use Editiel98\Factory;
use Editiel98\Forms\Fields\AbstractField;
use Error;
use Exception;

abstract class AbstractForm
{
    use TraitTypeOf;

    protected string $token = '';
    protected string $method = '';
    protected string $action = '';
    /**
     * @var array<AbstractField>
     */
    protected array $fields = [];
    protected string $class = '';
    protected string $id = "";

    /**
     * @var array<mixed>
     */
    protected array $dataset = [];
     /**
     * @var array<mixed>
     */
    protected array $datas = [];
    protected bool $multipart;
     /**
     * @var array<mixed>
     */
    protected array $inputsDatas = [];
     /**
     * @var array<mixed>
     */
    protected array $errorFields = [];
     /**
     * @var array<mixed>
     */
    protected array $errorFiles = [];

    private CSRFCheck $csrfCheck;

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
     * Return HTML Code form form with token
     * @return string
     */
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
     * Close HTML Code for form
     * @return string
     */
    public function endForm(): string
    {
        return "</form>";
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
     * @return array<AbstractField>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array<mixed> $datas  datas from HTML form
     * 
     * @return bool datas are OK
     */
    public function checkFormInputs(array $datas): bool
    {
        if (empty($datas))
            return false;
        if (!isset($datas['token'])) {
            return false;
        }
        $token = $datas['token'];
        if (!$this->csrfCheck->checkToken($token)) {
            $this->errorFields[] = 'token';
            return false;
        }

        return $this->testInputs($datas);
    }

    /**
     * @param array<mixed> $datas datas from HTML form
     * 
     * @return bool datas are OK
     */
    private function testInputs(array $datas): bool
    {
        foreach ($this->getFields() as $field) {
            //var_dump($field->getName(), $field->getTypeOf());
            $fieldName = $field->getName();
            //Modifier le if ->return false supprimer le else
            if (!array_key_exists($fieldName, $datas) && $field->isRequired() && $field->getTypeOf() !== self::TYPE_FILE) {
                $this->errorFields[] = $fieldName;
            } else {
                if (!empty($datas[$fieldName])) {
                    $this->processData($fieldName, $datas[$fieldName], $field);
                }
                if ($field->getTypeOf() === self::TYPE_FILE) {
                    $this->processFile($fieldName, $field);
                }
            }
        }
        $this->processError();
        return empty($this->errorFields);
    }

    /**
     * Check requirement for input data
     * @param string $name
     * @param string $value
     * @param AbstractField $field
     * 
     * @return bool : data is OK with model requirement
     */
    private function processData(string $name, string $value, AbstractField $field): bool
    {
        $value = trim($value);
        //On sécurise la donnée suivant le type
        $flag = null;
        switch ($field->getTypeOf()) {
            case  self::TYPE_BOOL:
                $filter = FILTER_VALIDATE_BOOL;
                $flag = FILTER_NULL_ON_FAILURE;
                break;
            case self::TYPE_NUMBER:
                $filter = FILTER_VALIDATE_INT;
                $flag = FILTER_NULL_ON_FAILURE;
                break;
            case self::TYPE_STRING:
                if ($value === '')
                    return false;
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
        try {
            $filteredValue = $this->filterValue($value, $field->getTypeOf(), $filter, $flag);
            if (is_null($filteredValue)) {
                $this->errorFields[] = $name;
                return false;
            }
            $this->inputsDatas[$name] = $filteredValue;
            return true;
        } catch (Exception $e) {
            throw new Exception('Error in data processing');
        } catch (Error $e) {
            throw new Exception('Error in data processing');
        }
    }

    /**
     * @param mixed $value
     * @param string $typeOf
     * @param int|null $filter
     * @param mixed|null $flag
     * 
     * @return mixed return filtered value or null if not ok
     */
    private function filterValue(mixed $value, string $typeOf, ?int $filter = null, mixed $flag = null): mixed
    {
        try {
            if (is_null($filter)) {
                return filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $flag), "flags" => FILTER_NULL_ON_FAILURE));
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
     * @param string $name : name of field
     * @param AbstractField $field
     * 
     * @return bool result.
     * Datas are stored in class's arrays
     */
    private function processFile(string $name,  AbstractField $field): bool
    {
        try {
            $nameField = $name;
            $multiple = false;
            $pos = strpos($name, '[');
            if ($pos) {
                $nameField = substr($name, 0, $pos);
                $multiple = true;
            }
            if (!array_key_exists($nameField, $_FILES) && $field->isRequired()) {
                $this->errorFields[] = $name;
                return false;
            }
            if ($multiple) {
                $files = $this->orderFiles($_FILES[$nameField]);
            } else {
                $files[] = $_FILES[$nameField];
            }
            $noError = true;
            foreach ($files as $file) {
                if ($file['error'] !== 0 || $file['name']==='') {
                    if($field->isRequired()){
                        $this->errorFields[] = $name;
                        $this->errorFiles[$name][] = array('name' => $file['name']);
                        $noError = false;
                    }
                    continue;
                }
                $this->inputsDatas[$nameField][] = array(
                    'tmp_name' => $file['tmp_name'],
                    'name' => $file['name'],
                    'size' => $file['size'],
                    'type' => $file['type'],
                    'full_path' => $file['full_path']
                );
            }
            return $noError;
        } catch (Exception $e) {
            throw new Exception('Error in file processing');
        } catch (Error $e) {
            throw new Exception('Error in file processing');
        }
    }

    /**
     * @param array<mixed> $filesDL
     * 
     * @return array<mixed>
     */
    private function orderFiles(array $filesDL): array
    {
        try {
            $arrayFiles = [];
            foreach ($filesDL as $title => $files) {
                foreach ($files as $idFile => $fieldValue) {
                    $arrayFiles[$idFile][$title] = $fieldValue;
                }
            }
            return $arrayFiles;
        } catch (Exception $e) {
            throw new Exception('Error in File field processing');
        } catch (Error $e) {
            throw new Exception('Error in File field processing');
        }
    }


   
    /**
     * @return array<mixed>|false
     */
    public function getInputsDatas(): array|false
    {
        if (empty($this->inputsDatas))
            return false;
        return $this->inputsDatas;
    }

    
    /**
     * @return array<mixed>
     */
    public function getErrorFields(): array
    {
        return $this->errorFields;
    }

    /**
     * @return void
     */
    private function processError(): void
    {
        try {
            foreach ($this->errorFields as $fieldItem) {
                $fieldError = $this->getField($fieldItem);
                $fieldError->setError(true);
            }
        } catch (Exception $e) {
            throw new Exception('Error in field error settings');
        } catch (Error $e) {
            throw new Exception('Error in field error settings');
        }
    }

    
    /**
     * @return array<mixed>
     */
    public function getErrorFiles(): array
    {
        return $this->errorFiles;
    }
}
