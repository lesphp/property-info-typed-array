<?php

namespace LesPhp\PropertyInfo\Tests;

use LesPhp\PropertyInfo\TypedArray;
use PHPUnit\Framework\TestCase;
use Attribute;

class TypedArrayTest extends TestCase
{
    public function testCreation()
    {
        $minimalTypedArray = new TypedArray('string');

        $this->assertEquals('string', $minimalTypedArray->getType());
        $this->assertFalse($minimalTypedArray->isNullable());
        $this->assertNull($minimalTypedArray->getKeyType());

        $typedArray = new TypedArray('string', true, 'int');

        $this->assertEquals('string', $typedArray->getType());
        $this->assertTrue($typedArray->isNullable());
        $this->assertEquals('int', $typedArray->getKeyType());
    }

    public function testAttribute()
    {
        $reflection = new \ReflectionClass(TypedArray::class);
        $attributes = $reflection->getAttributes(Attribute::class);

        $this->assertCount(1, $attributes);

        /** @var Attribute $instance */
        $instance = $attributes[0]->newInstance();

        $this->assertEquals(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE, $instance->flags);
    }
}
