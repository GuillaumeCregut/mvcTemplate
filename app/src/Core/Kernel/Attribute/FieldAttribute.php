<?php

namespace Editiel98\Kernel\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class FieldAttribute
{
    public function __construct(
        private string $type,
        private ?bool $allowBlank = false
    ) {
    }

        /**
         * Get the value of type
         */
    public function getType(): string
    {
            return $this->type;
    }

        /**
         * Set the value of type
         */



        /**
         * Get the value of AllowBlank
         */
    public function isAllowBlank(): ?bool
    {
            return $this->allowBlank;
    }
}
