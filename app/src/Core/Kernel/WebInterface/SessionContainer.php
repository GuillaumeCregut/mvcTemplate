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
        $_SESSION[$key][] = $value;
    }

    public function remove(string $key): void
    {
        parent::remove($key);
        unset($_SESSION[$key]);
    }

    public function setValue(string $key, mixed $value): void
    {
        parent::setValue($key, $value);
        $_SESSION[$key] = $value;
    }

    public function clear(): void
    {
        parent::clear();
        $_SESSION = array();
    }
}
