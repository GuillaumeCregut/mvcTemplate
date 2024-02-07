<?php

namespace Editiel98\Interfaces;

interface SessionInterface
{
    public function getKey(string $key);
    public function setKey(string $key, mixed $value);
    public function deleteKey(string $key);
    public function setMultipleKey(string $key, mixed $value);
}
