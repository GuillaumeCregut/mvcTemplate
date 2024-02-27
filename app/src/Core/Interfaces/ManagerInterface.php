<?php

namespace Editiel98\Interfaces;

use Editiel98\Kernel\Entity\AbstractEntity;

interface ManagerInterface
{
    /**
     * @return mixed[]
     */
    public function getAll(): array;

    public function getOneById(int $id): AbstractEntity | false;

    /**
     * @param string $fieldName
     * @param mixed $value
     *
     * @return mixed[]
     */
    public function getBy(string $fieldName, mixed $value): array | false;

    public function save(AbstractEntity $entity): bool;

    public function update(AbstractEntity $entity): bool;

    public function remove(AbstractEntity $entity): bool;
}
