<?php

namespace Transform\Visitor;

use Transform\Accessor\Accessor;
use Transform\Accessor\AccessorDispatcher;
use Transform\Mapping\Mapping;

class Visitor
{
    /**
     * @var Accessor
     */
    private $accessor;

    public function __construct(Accessor $accessor = null)
    {
        $this->accessor = $accessor ?: new AccessorDispatcher();
    }

    public function visit($source, $target, Mapping $mapping)
    {
        foreach ($this->accessor->getFields($source) as $field) {
            $fieldMapping = $mapping->getFieldMapping($field);

            $value = $this->accessor->getFieldValue($source, $field);

            $this->accessor->setFieldValue($target, $fieldMapping->getTargetName(), $value);
        }
    }
}
