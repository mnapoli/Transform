<?php

namespace Transform\Mapping;

class Mapping
{
    private $from;

    private $to;

    /**
     * @var FieldMapping[]
     */
    private $fields = [];

    public function __construct($from, $to)
    {
        $this->from = (string) $from;
        $this->to = (string) $to;
    }

    public static function fromArray(array $array)
    {
        if (! array_key_exists('from', $array)) {
            throw InvalidMappingException::missingRequiredField('from');
        }
        if (! array_key_exists('to', $array)) {
            throw InvalidMappingException::missingRequiredField('to');
        }

        $mapping = new self($array['from'], $array['to']);

        if (array_key_exists('fields', $array)) {
            foreach ($array['fields'] as $field => $fieldMapping) {
                $mapping->fields[$field] = FieldMapping::fromArray($field, $fieldMapping);
            }
        }

        return $mapping;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $field
     * @return FieldMapping
     */
    public function getFieldMapping($field)
    {
        if (! isset($this->fields[$field])) {
            return new FieldMapping($field);
        }

        return $this->fields[$field];
    }
}
