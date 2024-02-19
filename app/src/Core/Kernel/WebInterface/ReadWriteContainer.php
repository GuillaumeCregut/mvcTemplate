<?php

namespace Editiel98\Kernel\WebInterface;

class ReadWriteContainer extends ElementContainer
{
    public function remove(string $key): void
    {
        unset($this->params[$key]);
    }

    public function setValue(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }

    public function clear(): void
    {
        $this->params = [];
    }
}
