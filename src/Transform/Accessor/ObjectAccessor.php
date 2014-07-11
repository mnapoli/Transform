<?php

namespace Transform\Accessor;

class ObjectAccessor implements Accessor
{
    public function getFields($data)
    {
        $class = new \ReflectionClass($data);

        $properties = $class->getProperties();

        $properties = array_filter($properties, function (\ReflectionProperty $property) {
            return !$property->isStatic();
        });

        return array_map(function (\ReflectionProperty $property) {
            return $property->getName();
        }, $properties);
    }

    public function getFieldValue($data, $field)
    {
        $property = new \ReflectionProperty($data, $field);

        if (! $property->isPublic()) {
            $property->setAccessible(true);
        }

        return $property->getValue($data);
    }

    public function setFieldValue(&$data, $field, $value)
    {
        $property = new \ReflectionProperty($data, $field);

        if (! $property->isPublic()) {
            $property->setAccessible(true);
        }

        $property->setValue($data, $value);
    }

    public function supports($data)
    {
        return is_object($data);
    }
}
