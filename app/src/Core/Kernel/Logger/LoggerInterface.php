<?php

namespace Editiel98\Kernel\Logger;

interface LoggerInterface
{
    public function storeToFile(string $value): bool;
    public function loadFromFile(): array | bool;
}
