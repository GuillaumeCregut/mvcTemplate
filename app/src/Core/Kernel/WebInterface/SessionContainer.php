<?php

namespace Editiel98\Kernel\WebInterface;

class SessionContainer extends ReadWriteContainer
{
    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addValueToArray(string $key, mixed $value): void
    {
        $this->params[$key][] = $value;
    }
}
