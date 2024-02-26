<?php

namespace Editiel98\Kernel\Attribute\Validators;

use Editiel98\Kernel\Entity\AbstractEntity;
use ReflectionAttribute;
use ReflectionClass;

class GetConstraints
{
    /**
     * @var mixed[]
     */
    private static array $constraints = [];


    /**
     * @param AbstractEntity $entity
     *
     * @return mixed[]
     */
    public static function scanEntity(AbstractEntity $entity): array
    {
        $entityReflector = new ReflectionClass($entity);
        foreach ($entityReflector->getProperties() as $property) {
            self::$constraints[$property->name] = [];
            if (
                !empty($property->getAttributes(
                    \Editiel98\Kernel\Attribute\Constraints\AbstractConstraints::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ))
            ) {
                self::addAttribute($property->name, $property->getAttributes());
            }
        }
        return self::$constraints;
    }

    /**
     * @param mixed[] $attributes
     *
     * @return void
     */
    private static function addAttribute(string $name, array $attributes): void
    {
        foreach ($attributes as $attribute) {
            self::$constraints[$name][] = $attribute;
        }
    }
}
