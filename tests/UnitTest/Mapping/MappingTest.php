<?php

namespace tests\UnitTest\Mapping;

use Transform\Mapping\Mapping;

class MappingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_from_an_array()
    {
        $array = [
            'from' => 'array',
            'to' => 'array',
        ];

        $mapping = Mapping::fromArray($array);

        $this->assertInstanceOf(Mapping::class, $mapping);
    }
}
