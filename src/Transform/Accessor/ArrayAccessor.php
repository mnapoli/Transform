<?php

namespace Transform\Accessor;

class ArrayAccessor implements Accessor
{
    public function getFields($data)
    {
        return array_keys($data);
    }

    public function getFieldValue($data, $field)
    {
        return $data[$field];
    }

    public function setFieldValue($data, $field, $value)
    {
        $data[$field] = $value;
    }

    public function supports($data)
    {
        return is_array($data);
    }
}
