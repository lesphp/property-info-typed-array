<?php

namespace LesPhp\PropertyInfo;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use ReflectionClass;
use ReflectionType;
use ReflectionUnionType;
use ReflectionException;
use ReflectionIntersectionType;

class TypedArrayAttributeExtractor implements PropertyTypeExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function getTypes(string $class, string $property, array $context = [])
    {
        try {
            $reflectionClass = new ReflectionClass($class);
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionPropertyType = $reflectionProperty->getType();
            $typedArrayReflections = $reflectionProperty->getAttributes(TypedArray::class);

            if (
                null === $reflectionPropertyType
                || !$this->hasArrayType($reflectionPropertyType)
                || count($typedArrayReflections) === 0
            ) {
                return null;
            }

            $types = [];

            foreach ($typedArrayReflections as $typedArrayReflection) {
                /** @var TypedArray $typedArray */
                $typedArray = $typedArrayReflection->newInstance();
                $arrayType = $typedArray->getType();
                $lcArrayType = strtolower($arrayType);
                $nullable = $typedArray->isNullable();

                $collectionKeyType = $typedArray->getKeyType() !== null ? new Type(
                    $typedArray->getKeyType(),
                    false
                ) : null;

                if (Type::BUILTIN_TYPE_ARRAY === $lcArrayType) {
                    $collectionValueType = new Type(Type::BUILTIN_TYPE_ARRAY, $nullable, null, true);
                } elseif (in_array($lcArrayType, Type::$builtinTypes)) {
                    $collectionValueType = new Type($lcArrayType, $nullable);
                } else {
                    $collectionValueType = new Type(
                        Type::BUILTIN_TYPE_OBJECT,
                        $nullable,
                        $this->resolveTypeName($arrayType, $reflectionClass)
                    );
                }

                $types[] = new Type(
                    Type::BUILTIN_TYPE_ARRAY,
                    $reflectionPropertyType->allowsNull(),
                    null,
                    true,
                    $collectionKeyType,
                    $collectionValueType
                );
            }

            return $types;
        } catch (ReflectionException) {
            return null;
        }
    }

    private function hasArrayType(ReflectionType $reflectionType): bool
    {
        if (
            $reflectionType instanceof ReflectionUnionType
            || (
                class_exists(
                    'ReflectionIntersectionType',
                    false
                ) && $reflectionType instanceof ReflectionIntersectionType
            )
        ) {
            $types = $reflectionType->getTypes();
        } else {
            $types = [$reflectionType];
        }

        return count(
            array_filter($types, fn (ReflectionType $type) => $type->getName() === Type::BUILTIN_TYPE_ARRAY)
        ) > 0;
    }

    private function resolveTypeName(string $name, ReflectionClass $declaringClass): string
    {
        $lcName = strtolower($name);

        if ('self' === $lcName || 'static' === $lcName) {
            return $declaringClass->name;
        } elseif ('parent' === $lcName && $parent = $declaringClass->getParentClass()) {
            return $parent->name;
        }

        return $name;
    }
}
