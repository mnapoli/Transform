<?php

namespace Transform\Mapping;

class FieldMapping
{
    private $name;

    private $targetName;

    public function __construct($name)
    {
        $this->name = (string) $name;
    }

    public static function fromArray($name, array $array)
    {
        $mapping = new self($name);

        if (array_key_exists('target_name', $array)) {
            $mapping->targetName = (string) $array['target_name'];
        }

        return $mapping;
    }

    /**
     * @return string
     */
    public function getTargetName()
    {
        return $this->targetName ?: $this->name;
    }
}
