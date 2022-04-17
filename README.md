## Typed array attribute for symfony/property-info

This is a package that offer an PHP native attribute for symfony/property-info retrieve correct types of array elements.
Now days, this is only possible with DocBlock annotation. DocBlocks is PHP first-class citizen, but it isn't for type checking/enforcing.
A wrong class name inside a docblock is only textual documentation for PHP, but not for developer. Then, documentations can be removed/replaced and
the program behavior should be the same.

## Instalation

```
composer require lesphp/property-info-typed-array
```

## Compatibility

| symfony/property-info | les/property-info-typed-array |
|-----------------------|-------------------------------|
| 6.x                   | 6.x                           |

## Usage

The TypedArray attribute is repeatable, them multiples annotations are evaluated as a OR clause.

```php
use LesPhp\PropertyInfo\TypedArray;
use LesPhp\PropertyInfo\TypedArrayAttributeExtractor;

class FooBar {
    #[TypedArray(type: Baz::class, nullable: false, keyType: 'int')]
    private array $baz;
    
    #[TypedArray(type: Baz::class, nullable: true)]
    #[TypedArray(type: 'string', nullable: true)]
    private array $bazOrString;
}

$typedArrayExtractor = new TypedArrayAttributeExtractor();

$typedArrayExtractor->getTypes(Foo_Bar::class, 'baz');
$typedArrayExtractor->getTypes(Foo_Bar::class, 'bazOrString');
```

In combination with symfony/serializer, this can be a powerful annotation and dispense requirement of DocBlock annotation readers.

```php
use LesPhp\PropertyInfo\TypedArrayAttributeExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader());
$propertyTypeExtractor = new PropertyInfoExtractor([new ReflectionExtractor()], [new TypedArrayAttributeExtractor()]);
$propertyNormalizer = new PropertyNormalizer($classMetadataFactory, null, $propertyTypeExtractor);

$serializer = new Serializer(
    [$propertyNormalizer, new ArrayDenormalizer()],
    [new JsonEncoder()]
);
```