<?php

namespace Transform\Mapping;

/**
 * The mapping is invalid.
 */
class InvalidMappingException extends \Exception
{
    public static function missingRequiredField($field)
    {
        return new self(sprintf('Invalid mapping: "%s" was not set', $field));
    }
}
