<?php

namespace Editiel98\Interfaces;

interface DatabaseInterface
{
    public static function getInstance(): self;
    /**
     * @param string $statement
     * @param string|null $className
     *
     * @return mixed[]
     */
    public function query(string $statement, ?string $className = null): array;
    /**
     * @param string $statement
     * @param string|null $className
     * @param mixed[]|null $values
     * @param bool|null $single
     *
     * @return mixed
     */
    public function preparedQuery(
        string $statement,
        ?string $className,
        ?array $values = [],
        ?bool $single = false
    ): mixed;
    public function execStraight(string $query): bool;
    /**
     * @param string $statement
     * @param mixed[] $values
     *
     * @return bool
     */
    public function exec(string $statement, array $values): bool | int;
    public function startTransac(): void;
    public function commitTransc(): void;
    public function rollBack(): void;
}
