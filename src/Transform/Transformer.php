<?php

namespace Transform;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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

    public function transform($object)
    {
        $configuration = $this->configuration[get_class($object)];

        $data = [];
        foreach ($configuration['fields'] as $property => $propertyConfig) {
            // Shortcut
            if (is_int($property)) {
                $property = $propertyConfig;
                $propertyConfig = [];
            }

            $data[$property] = $this->readField($object, $property, $propertyConfig);
        }

        return $data;
    }

    public function addConfiguration(array $configuration)
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
            throw new \Exception('Unable to resolve ' . $get);
        }

        return $get();
    }
}
