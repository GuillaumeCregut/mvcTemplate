<?php

namespace Editiel98\Interfaces;

interface SessionInterface
{
    public function getKey(string $key):mixed;
    public function setKey(string $key, mixed $value): void;
    public function deleteKey(string $key): void;
    public function setMultipleKey(string $key, mixed $value): void;
}
