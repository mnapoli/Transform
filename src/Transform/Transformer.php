<?php

namespace Transform;

use Transform\Mapping\Mapping;

class Transformer
{
    /**
     * @var Mapping[]
     */
    private $mappings = [];

    public function transform($data, $target)
    {
        $mapping = $this->getMapping($this->getType($data), $this->getType($target));

        return $data;
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
}
