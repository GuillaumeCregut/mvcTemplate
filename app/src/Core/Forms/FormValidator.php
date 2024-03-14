<?php

namespace Editiel98\Forms;

use Editiel98\Kernel\Attribute\Validators\GetConstraints;
use Editiel98\Kernel\Attribute\Validators\Validator;
use InvalidArgumentException;

class FormValidator
{
    /**
     * @var mixed[]
     */
    private array $errorInputs = [];
    /**
     * @param string $entityName
     * @param mixed[] $values
     *
     * @return bool
     */
    public function validate(string $entityName, array $values): bool
    {
        if (
            !class_exists($entityName)
            || get_parent_class($entityName) !== \Editiel98\Kernel\Entity\AbstractEntity::class
        ) {
            throw new InvalidArgumentException();
        }
        if (empty($values)) {
            return false;
        }
        $constraints = GetConstraints::scanEntity($entityName);
        $result = Validator::validate($values, $constraints);
        if (is_array($result)) {
            $this->errorInputs = $result;
            return false;
        }
        return $result;
    }

    /**
     * @return mixed[]
     */
    public function getErrorInputs(): array
    {
        return $this->errorInputs;
    }
}
