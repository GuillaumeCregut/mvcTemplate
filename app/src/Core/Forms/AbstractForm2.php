<?php

namespace Editiel98\Forms;

use Editiel98\Forms\Fields\AbstractField;
use Editiel98\Kernel\WebInterface\RequestHandler;
use Error;
use Exception;

abstract class AbstractForm2 extends AbstractForm
{
    /**
     * @var array<mixed>
     */
    protected array $inputsDatas = [];

    /**
     * @var array<mixed>
     */
    protected array $errorFiles = [];

    /**
     * @var array<mixed>
     */
    protected array $errorFields = [];

    /**
     * Check requirement for input data
     * @param string $name
     * @param string $value
     * @param AbstractField $field
     *
     * @return bool : data is OK with model requirement
     */


    /**
     * @param array<mixed> $files
     * @param AbstractField $field
     * @param string $name
     * @param string $nameField
     * @return bool
     */
    // private function parseFiles(array $files, AbstractField $field, string $name, string $nameField): bool
    // {
    //     $noError = true;
    //     foreach ($files as $file) {
    //         if ($file['error'] !== 0 || $file['name'] === '') {
    //             if ($field->isRequired()) {
    //                 $this->errorFields[] = $name;
    //                 $this->errorFiles[$name][] = array('name' => $file['name']);
    //                 $noError = false;
    //             }
    //             continue;
    //         }
    //         $this->inputsDatas[$nameField][] = array(
    //             'tmp_name' => $file['tmp_name'],
    //             'name' => $file['name'],
    //             'size' => $file['size'],
    //             'type' => $file['type'],
    //             'full_path' => $file['full_path']
    //         );
    //     }
    //     return $noError;
    // }

    /**
     * @param string $name : name of field
     * @param AbstractField $field
     *
     * @return bool result.
     * Datas are stored in class's arrays
     */
    // private function processFile(string $name, AbstractField $field): bool
    // {
    //     $requestHandler = RequestHandler::getInstance();
    //     $filesFromRH = $requestHandler->files;
    //     $filesFromForm = $filesFromRH->getAll();
    //     try {
    //         $nameField = $name;
    //         $multiple = false;
    //         $pos = strpos($name, '[');
    //         if ($pos) {
    //             $nameField = substr($name, 0, $pos);
    //             $multiple = true;
    //         }
    //         if (!array_key_exists($nameField, $filesFromForm) && $field->isRequired()) {
    //             $this->errorFields[] = $name;
    //             return false;
    //         }
    //         if ($multiple) {
    //             $files = $this->orderFiles($filesFromForm[$nameField]);
    //         } else {
    //             $files[] = $filesFromForm[$nameField];
    //         }
    //         return $this->parseFiles($files, $field, $name, $nameField);
    //     } catch (Exception $e) {
    //         throw new Exception('Error in file processing');
    //     } catch (Error $e) {
    //         throw new Exception('Error in file processing');
    //     }
    // }

    /**
     * @param array<mixed> $filesDL
     *
     * @return array<mixed>
     */
    // private function orderFiles(array $filesDL): array
    // {
    //     try {
    //         $arrayFiles = [];
    //         foreach ($filesDL as $title => $files) {
    //             foreach ($files as $idFile => $fieldValue) {
    //                 $arrayFiles[$idFile][$title] = $fieldValue;
    //             }
    //         }
    //         return $arrayFiles;
    //     } catch (Exception $e) {
    //         throw new Exception('Error in File field processing');
    //     } catch (Error $e) {
    //         throw new Exception('Error in File field processing');
    //     }
    // }

    /**
     * @return array<mixed>
     */
    public function getErrorFields(): array
    {
        return $this->errorFields;
    }

    /**
     * @return array<mixed>
     */
    public function getErrorFiles(): array
    {
        return $this->errorFiles;
    }
}
