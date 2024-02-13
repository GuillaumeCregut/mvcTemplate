<?php
namespace Editiel98\Chore\Attribute;

use Attribute;

#[Attribute()]
class RouteAttribute
{
    public function __construct(
        private string $path,
        private ?array $datas=[],
        private ?string $name=''
    )
    {
        
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