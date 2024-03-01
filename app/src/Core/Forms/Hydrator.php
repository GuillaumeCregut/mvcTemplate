<?php

namespace Editiel98\Forms;

use Editiel98\Kernel\Entity\AbstractEntity;
use Exception;
use InvalidArgumentException;
use ReflectionClass;

class Hydrator
{
    /**
     * @var mixed[]
     */
    private array $notSetValues = [];

    public function __construct()
    {
    }

    /**
     * @param class-string $classname
     * @param mixed[] $values
     *
     * @return AbstractEntity
     */
    public function hydrate(string $classname, array $values): AbstractEntity
    {
        $classProperties = [];
        if (!class_exists($classname)) {
            throw new InvalidArgumentException(sprintf('Class %s does not exists', $classname));
        }
        if (empty($values)) {
            throw new InvalidArgumentException('No values provided');
        }
        $reflector = new ReflectionClass($classname);
        $entity = new $classname();
        $reflectorProperties = $reflector->getProperties();
        foreach ($reflectorProperties as $reflectorProperty) {
            $name = $reflectorProperty->getName();
            $typeProperty = $reflectorProperty->getType();
            if ($typeProperty instanceof \ReflectionNamedType) {
                $type = $typeProperty->getName();
            } else {
                throw new Exception('Unknown Error');
            }
            $classProperties[$name] = $type;
            if (array_key_exists($name, $values)) {
                $this->addElement($entity, $name, $type, $values[$name]);
            }
        }
        //Store appart keys that are not in entity
        foreach ($values as $key => $value) {
            if (!array_key_exists($key, $classProperties)) {
                $this->notSetValues[$key] = $value;
            }
        }
        return $entity;
    }

    /**
     * @param AbstractEntity $entity
     * @param string $property
     * @param string $type
     * @param mixed $value
     *
     * @return void
     */
    private function addElement(AbstractEntity &$entity, string $property, string $type, mixed $value): void
    {
        //TODO pacify value
        $method = 'set' . ucfirst($property);
        switch ($type) {
            case 'string':
                $newValue = (string) $value;
                break;
            case 'int':
                $newValue = (int) $value;
                break;
            case 'bool':
                $newValue = (bool) $value;
                break;
            case 'float':
                $newValue = (float) $value;
                break;
            default:
                throw new InvalidArgumentException(sprintf("Type %s is not allowed to be set by form", $type));
        }
        $entity->$method($newValue);
    }

    /**
     * @return mixed[]
     */
    public function getNotSetValues(): array
    {
        return $this->notSetValues;
    }
}
