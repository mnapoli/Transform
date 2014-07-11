<?php

namespace Transform\Accessor;

class AccessorDispatcher implements Accessor
{
    /**
     * @var Accessor[]
     */
    private $accessors = [];

    public function __construct(array $accessors = null)
    {
        $this->accessors = ($accessors !== null) ? $accessors : [
            new ArrayAccessor(),
            new ObjectAccessor(),
        ];
    }

    public function getFields($data)
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->supports($data)) {
                return $accessor->getFields($data);
            }
        }

        throw new \RuntimeException();
    }

    public function getFieldValue($data, $field)
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->supports($data)) {
                return $accessor->getFieldValue($data, $field);
            }
        }

        throw new \RuntimeException();
    }

    public function setFieldValue(&$data, $field, $value)
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->supports($data)) {
                return $accessor->setFieldValue($data, $field, $value);
            }
        }

        throw new \RuntimeException();
    }

    public function supports($data)
    {
        foreach ($this->accessors as $accessor) {
            if ($accessor->supports($data)) {
                return true;
            }
        }

        return false;
    }
}
