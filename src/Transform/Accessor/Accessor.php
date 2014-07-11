<?php

namespace Transform\Accessor;

interface Accessor
{
    public function getFields($data);

    public function getFieldValue($data, $field);

    public function setFieldValue($data, $field, $value);

    /**
     * @param mixed $data
     * @return boolean
     */
    public function supports($data);
}
