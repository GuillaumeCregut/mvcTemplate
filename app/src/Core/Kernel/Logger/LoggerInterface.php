<?php

namespace Editiel98\Kernel\Logger;

interface LoggerInterface
{
    public function storeToFile(string $value): bool;
    /**
     * @return array<mixed>|bool
     */
    public function loadFromFile(): array | bool;
}
