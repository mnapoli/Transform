<?php

namespace Transform;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Transformer
{
    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public function __construct(PropertyAccessor $accessor = null)
    {
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function transform($from, $to)
    {
        if (is_object($from)) {
            $from = $this->normalize($from);
        }

        if ($to === 'array') {
            return $from;
        }

        if (is_string($to) && class_exists($to)) {
            $to = new $to();
        }

        if (is_object($to)) {
            return $this->denormalize($from, $to);
        }

        throw new \InvalidArgumentException('Unsupported format ' . $to);
    }

    private function normalize($object)
    {
        $configuration = $this->configuration[get_class($object)];

        $data = [];
        foreach ($configuration['fields'] as $property => $propertyConfig) {
            // Shortcut
            if (is_int($property)) {
                $property = $propertyConfig;
                $propertyConfig = [];
            }

            if (isset($propertyConfig['get']) && $propertyConfig['get'] === null) {
                // Not readable
                continue;
            }

            $value = $this->readField($object, $property, $propertyConfig);

            if (is_resource($value)) {
                throw new TransformationException('Cannot normalize resources');
            }

            if (is_object($value)) {
                $value = $this->transform($value, 'array');
            }

            $data[$property] = $value;
        }

        return $data;
    }

    private function denormalize($data, $object)
    {
        $configuration = $this->configuration[get_class($object)];

        foreach ($configuration['fields'] as $property => $propertyConfig) {
            // Shortcut
            if (is_int($property)) {
                $property = $propertyConfig;
                $propertyConfig = [];
            }

            if (!array_key_exists($property, $data)) {
                throw new TransformationException('Missing field ' . $property);
            }

            $value = $data[$property];

            if (isset($propertyConfig['type'])) {
                $value = $this->transform($value, $propertyConfig['type']);
            }

            $this->writeField($object, $property, $propertyConfig, $value);
        }

        return $object;
    }

    public function addMapping(array $configuration)
    {
        $this->configuration = array_merge($this->configuration, $configuration);
    }

    private function readField($object, $property, array $propertyConfig)
    {
        if (! array_key_exists('get', $propertyConfig)) {
            return $this->accessor->getValue($object, $property);
        }

        $get = $propertyConfig['get'];

        // "method()" becomes [$object, 'method']
        if (is_string($get) && substr($get, -2) === '()') {
            $method = substr($get, 0, strlen($get) - 2);
            $get = [$object, $method];
        }

        if (! is_callable($get)) {
            throw new TransformationException($get . ' is not callable');
        }

        if ($get instanceof \Closure) {
            $get = $get->bindTo($object, $object);
        }

        return $get();
    }

    private function writeField($object, $property, array $propertyConfig, $value)
    {
        if (! array_key_exists('set', $propertyConfig)) {
            $this->accessor->setValue($object, $property, $value);
            return;
        }

        $set = $propertyConfig['set'];

        if ($set === null) {
            // Not writable
            return;
        }

        // "method()" becomes [$object, 'method']
        if (is_string($set) && substr($set, -2) === '()') {
            $method = substr($set, 0, strlen($set) - 2);
            $set = [$object, $method];
        }

        if (! is_callable($set)) {
            throw new TransformationException($set . ' is not callable');
        }

        if ($set instanceof \Closure) {
            $set = $set->bindTo($object, $object);
        }

        $set($value);
    }
}
