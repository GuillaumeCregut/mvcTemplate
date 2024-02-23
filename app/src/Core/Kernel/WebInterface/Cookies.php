<?php

namespace Editiel98\Kernel\WebInterface;

class Cookies
{
    /**
     * @var mixed[]
     */
    private array $cookies = [];
    private static ?Cookies $instance = null;

    public static function getInstance(): Cookies
    {
        if (self::$instance === null) {
            self::$instance = new Cookies();
        }
        return self::$instance;
    }


    /**
     * @return mixed[]
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param mixed[] $cookies
     * @return self
     */
    public function replace(array $cookies): self
    {
        $this->cookies = $cookies;
        return $this;
    }


    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, mixed $value): self
    {
        $this->cookies[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return self
     */
    public function remove(string $key): self
    {
        unset($this->cookies[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return !empty($this->cookies[$key]);
    }

    /**
     * @return self
     */
    public function clean(): self
    {
        $this->cookies = [];
        return $this;
    }
}
