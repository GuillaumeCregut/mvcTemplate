<?php

namespace Editiel98\Kernel\WebInterface;

class Headers
{
    /**
     * @var mixed[]
     */
    private array $headers = [];

    /**
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param mixed[] $headers
     * @return self
     */
    public function replace(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return self
     */
    public function clean(): self
    {
        $this->headers = [];
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return !empty($this->headers[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, mixed $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return self
     */
    public function remove(string $key): self
    {
        unset($this->headers[$key]);
        return $this;
    }
}
