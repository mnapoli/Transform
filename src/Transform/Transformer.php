<?php

namespace Transform;

use Transform\Mapping\Mapping;
use Transform\Visitor\Visitor;

class Transformer
{
    /**
     * @var Mapping[]
     */
    private $mappings = [];

    /**
     * @var Visitor
     */
    private $visitor;

    public function __construct()
    {
        $this->visitor = new Visitor();
    }

    public function transform($data, $target)
    {
        $mapping = $this->getMapping($this->getType($data), $this->getType($target));

        $result = $this->create($target);

        $this->visitor->visit($data, $result, $mapping);

        return $result;
    }

    public function addMapping(Mapping $mapping)
    {
        $this->mappings[] = $mapping;
    }

    private function getMapping($from, $to)
    {
        foreach ($this->mappings as $mapping) {
            if ($mapping->getFrom() === $from && $mapping->getTo() === $to) {
                return $mapping;
            }
        }

        // Default empty mapping
        return new Mapping($from, $to);
    }

    private function getType($data)
    {
        if (is_string($data)) {
            return $data;
        }

        if (is_array($data)) {
            return 'array';
        }

        if (is_object($data)) {
            return get_class($data);
        }

        throw new \RuntimeException('Unknown type');
    }

    private function create($type)
    {
        if (is_array($type) || is_object($type)) {
            return $type;
        }

        if (is_string($type)) {
            if ($type === 'array') {
                return [];
            }
            if ($type === 'stdClass') {
                return new \stdClass();
            }

            if (! class_exists($type)) {
                throw new \RuntimeException('Unknown class ' . $type);
            }

            return (new \ReflectionClass($type))->newInstanceWithoutConstructor();
        }

        throw new \RuntimeException('Unknown type');
    }
}
