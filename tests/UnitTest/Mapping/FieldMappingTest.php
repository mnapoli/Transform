<?php

namespace tests\UnitTest\Mapping;

use Transform\Mapping\FieldMapping;

class FieldMappingTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_can_be_created_from_an_array()
    {
        $mapping = FieldMapping::fromArray('foo', []);
        $this->assertInstanceOf(FieldMapping::class, $mapping);
    }

    /**
     * @test
     */
    public function target_name_should_default_to_the_field_name()
    {
        $mapping = FieldMapping::fromArray('foo', []);
        $this->assertEquals('foo', $mapping->getTargetName());
    }

    /**
     * @test
     */
    public function target_name_can_be_overridden()
    {
        $array = [
            'target_name' => 'bar',
        ];

        $mapping = FieldMapping::fromArray('foo', $array);
        $this->assertEquals('bar', $mapping->getTargetName());
    }
}
