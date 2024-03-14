<?php

namespace Editiel98\Kernel\Attribute\Validators;

use Editiel98\Kernel\Exception\ValidationExceptionEmpty;
use Editiel98\Kernel\WebInterface\RequestHandler;

class Validator
{
    /**
     * @param mixed[] $fields
     * @param mixed[] $validatorsArray
     *
     * @return mixed[]
     */
    public static function validate($fields, $validatorsArray): array | bool
    {
        $errorArray = [];
        if (empty($fields)) {
            throw new ValidationExceptionEmpty();
        }
        if (empty($validatorsArray)) {
            return false;
        }
        foreach ($validatorsArray as $fieldname => $validators) {
            if ($fieldname === 'id') {
                continue;
            }
            if (array_key_exists($fieldname, $fields)) {
                $value = $fields[$fieldname];
                self::checkField($validators, $value, $fieldname, $errorArray);
            } else {
                //$fields n'existe pas dans le formulaire
                //Vérifier si il y a un validator bool
                $isBoolField = self::isCheckBox($validators);
                if ($isBoolField) {
                    $handler = RequestHandler::getInstance();
                    $handler->request->setValue($fieldname, null);
                    continue;
                }
                if (empty($validators)) {
                    continue;
                }
                $errorArray[$fieldname] = array('state' => 'missing');
            }
        }
        if (count($errorArray) === 0) {
            return true;
        } else {
            return $errorArray;
        }
    }

    /**
     * @param mixed[] $validators
     * @param mixed $value
     * @param mixed[] $errorArray
     *
     * @return void
     */
    private static function checkField(array $validators, mixed $value, string $fieldname, array &$errorArray): void
    {
        $count = 0;
        foreach ($validators as $validator) {
            $newValidator = $validator->newInstance();
            if (!$newValidator->isOk($value)) {
                $errorArray[$fieldname]['error_' . $count] = $newValidator->getMessage();
            }
            $count++;
        }
    }

    /**
     * @param mixed[] $validators
     *
     * @return bool
     */
    private static function isCheckBox(array $validators): bool
    {
        foreach ($validators as $validator) {
            if ($validator->getName() === 'Editiel98\Kernel\Attribute\Constraints\CheckBoxConstraint') {
                return true;
            }
        }
        return false;
    }
}
