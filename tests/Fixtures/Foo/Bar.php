<?php

namespace LesPhp\PropertyInfo\Tests\Fixtures\Foo;

use LesPhp\PropertyInfo\Tests\Fixtures\Foo\Bar\Baz;
use LesPhp\PropertyInfo\TypedArray;

class Bar extends ParentBar
{
    private array $noAttribute;

    #[TypedArray(type: Baz::class)]
    private int $noArray;

    #[TypedArray(type: Baz::class)]
    private array|\stdClass $bazUnionType;

    #[TypedArray(type: Baz::class)]
    private array $baz;

    #[TypedArray(type: Baz::class, nullable: true)]
    private array $bazNullable;

    #[TypedArray(type: 'int', keyType: 'string')]
    private array $stringKeyWithIntValue;

    #[TypedArray(type: 'array', nullable: true, keyType: 'int')]
    private array $intKeyWithArrayValueNullable;

    #[TypedArray(type: 'int', keyType: 'string')]
    #[TypedArray(type: 'string', keyType: 'int')]
    private array $multipleTypes;

    #[TypedArray(type: 'int', keyType: 'string')]
    private ?array $nullable;

    #[TypedArray(type: 'self')]
    private array $selfType;

    #[TypedArray(type: 'static')]
    private array $staticType;

    #[TypedArray(type: 'parent')]
    private array $parentType;
}
