<?php

namespace Editiel98\Kernel\WebInterface;

use Editiel98\Kernel\Exception\WebInterfaceException;

abstract class ElementContainer
{
    /**
     * @var mixed[]
     */
    protected array $params = [];

    /**
     * @param mixed[] $params
     */
    public function __construct(array $params)
    {
        $this->add($params);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParam(string $key): mixed
    {
        if (!$this->asKey($key)) {
            throw new WebInterfaceException(sprintf("Key %s not found", $key));
        }
        return $this->params[$key];
    }

    public function asKey(string $key): bool
    {
        return isset($this->params[$key]);
    }

    /**
     * @return mixed[] array
     */
    public function getAll(): array
    {
        return $this->params;
    }


    /**
     * @param mixed[] $datas
     *
     * @return void
     */
    protected function add(array $datas): void
    {
        foreach ($datas as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    private function set(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }
}
