<?php

namespace Editiel98\Forms;

use Editiel98\Forms\Fields\AbstractField;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Error;
use Exception;

abstract class AbstractForm extends AbstractMiniForm
{
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

    /**
     * @param array<mixed> $datas  datas from HTML form
     *
     * @return bool datas are OK
     */
    public function checkFormInputs(array $datas): bool
    {
        if (empty($datas)) {
            return false;
        }
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
            $fieldName = $field->getName();
            //Modifier le if ->return false supprimer le else
            if (
                !array_key_exists($fieldName, $datas)
                && $field->isRequired()
                && $field->getTypeOf() !== self::TYPE_FILE
            ) {
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
        $filterFlag = $this->setFilter($field->getTypeOf(), $value);
        if (!$filterFlag) {
            return false;
        }
        $filter = $filterFlag[0];
        $flag = $filterFlag[1];
        try {
            $filteredValue = $this->filterValue($value, $filter, $flag);
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
     * @param array<mixed> $files
     * @param AbstractField $field
     * @param string $name
     * @param string $nameField
     * @return bool
     */
    private function parseFiles(array $files, AbstractField $field, string $name, string $nameField): bool
    {
        $noError = true;
        foreach ($files as $file) {
            if ($file['error'] !== 0 || $file['name'] === '') {
                if ($field->isRequired()) {
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
    }

    /**
     * @param string $name : name of field
     * @param AbstractField $field
     *
     * @return bool result.
     * Datas are stored in class's arrays
     */
    private function processFile(string $name, AbstractField $field): bool
    {
        $requestHandler = RequestHandler::getInstance();
        $filesFromRH = $requestHandler->files;
        $filesFromForm = $filesFromRH->getAll();
        try {
            $nameField = $name;
            $multiple = false;
            $pos = strpos($name, '[');
            if ($pos) {
                $nameField = substr($name, 0, $pos);
                $multiple = true;
            }
            if (!array_key_exists($nameField, $filesFromForm) && $field->isRequired()) {
                $this->errorFields[] = $name;
                return false;
            }
            if ($multiple) {
                $files = $this->orderFiles($filesFromForm[$nameField]);
            } else {
                $files[] = $filesFromForm[$nameField];
            }
            return $this->parseFiles($files, $field, $name, $nameField);
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
        if (empty($this->inputsDatas)) {
            return false;
        }
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
