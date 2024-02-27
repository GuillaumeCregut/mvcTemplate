<?php

namespace Editiel98\Kernel\Attribute\Validators;

use Editiel98\Kernel\Exception\ValidationExceptionEmpty;

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
        foreach ($validators as $validator) {
            $newValidator = $validator->newInstance();
            if (!$newValidator->isOk($value)) {
                $errorArray[$fieldname][] = $newValidator->getMessage();
            }
        }
    }
}
