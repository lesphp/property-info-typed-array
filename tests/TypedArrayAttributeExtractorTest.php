<?php

namespace LesPhp\PropertyInfo\Tests;

use LesPhp\PropertyInfo\Tests\Fixtures\Foo\Bar;
use LesPhp\PropertyInfo\Tests\Fixtures\Foo\ParentBar;
use LesPhp\PropertyInfo\TypedArrayAttributeExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Type;

class TypedArrayAttributeExtractorTest extends TestCase
{
    private TypedArrayAttributeExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new TypedArrayAttributeExtractor();
    }

    /**
     * @dataProvider typesProvider
     */
    public function testExtractors(string $property, array $type = null)
    {
        $this->assertEquals($type, $this->extractor->getTypes(Bar::class, $property));
    }

    public function typesProvider()
    {
        return [
            ['noAttribute', null],
            ['noArray', null],
            ['nonExistent', null],
            ['baz', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, false, Bar\Baz::class))]],
            ['bazUnionType', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, false, Bar\Baz::class))]],
            ['bazNullable', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, true, Bar\Baz::class))]],
            ['stringKeyWithIntValue', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING, false), new Type(Type::BUILTIN_TYPE_INT, false))]],
            ['intKeyWithArrayValueNullable', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT, false), new Type(Type::BUILTIN_TYPE_ARRAY, true, null, true))]],
            ['multipleTypes', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING, false), new Type(Type::BUILTIN_TYPE_INT, false)), new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT, false), new Type(Type::BUILTIN_TYPE_STRING, false))]],
            ['nullable', [new Type(Type::BUILTIN_TYPE_ARRAY, true, null, true, new Type(Type::BUILTIN_TYPE_STRING, false), new Type(Type::BUILTIN_TYPE_INT, false))]],
            ['selfType', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, false, Bar::class))]],
            ['staticType', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, false, Bar::class))]],
            ['parentType', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, new Type(Type::BUILTIN_TYPE_OBJECT, false, ParentBar::class))]],
        ];
    }
}
