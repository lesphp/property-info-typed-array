<?php

namespace LesPhp\PropertyInfo;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class TypedArray
{
    public function __construct(
        private string $type,
        private bool $nullable = false,
        private ?string $keyType = null
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getKeyType(): ?string
    {
        return $this->keyType;
    }
}
