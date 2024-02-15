<?php

namespace Editiel98\Kernel\Attribute;

use Attribute;

#[Attribute()]
class RouteAttribute
{
        /**
         * @var array<mixed>
         */
    private array $datas = [];

        /**
         * Undocumented function
         *
         * @param string $path
         * @param array<mixed>|null $datas
         * @param string|null $name
         */
    public function __construct(
        private string $path,
        ?array $datas = [],
        private ?string $name = ''
    ) {
            $this->datas = $datas;
    }

        /**
         * Get the value of path
         */
    public function getPath(): string
    {
            return $this->path;
    }

        /**
         * Get the value of datas
         */
        /**
         * @return array<mixed>|null
         */
    public function getDatas(): ?array
    {
            return $this->datas;
    }

        /**
         * Get the value of name
         */
    public function getName(): ?string
    {
            return $this->name;
    }
}
