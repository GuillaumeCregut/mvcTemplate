<?php

namespace Editiel98\Kernel\WebInterface;

class Cookies
{
    /**
     * @var Cookie[]
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
     *
     * @param string $name
     * @param mixed $value
     * @param string|null $expire
     * @param string|null $domain
     * @param string|null $path
     * @param boolean|null $secure
     * @param boolean|null $httpOnly
     * @param string|null $sameSite
     * @return self
     */
    public function set(
        string $name,
        mixed $value,
        ?string $expire,
        ?string $domain,
        ?string $path,
        ?bool $secure,
        ?bool $httpOnly,
        ?string $sameSite
    ): self {
        $cookie = new Cookie($name, $value, $expire, $domain, $path, $secure, $httpOnly, $sameSite);
        $this->cookies[$name] = $cookie;
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

    public function deleteCookie(string $name): self
    {
        $this->cookies[$name]->setValue('');
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
