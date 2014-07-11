<?php

namespace tests\FunctionalTest;

use Transform\Mapping\Mapping;
use Transform\Transformer;

class TransformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function transform_a_simple_array()
    {
        $source = [
            'foo' => 'bar',
        ];

        $mapping = [
            'from' => 'array',
            'to' => 'array',
            'fields' => [
                'foo' => [
                    'target_name' => 'blah',
                ],
            ],
        ];

        $expected = [
            'blah' => 'bar',
        ];

        $transformer = new Transformer();
        $transformer->addMapping(Mapping::fromArray($mapping));

        $result = $transformer->transform($source, 'array');

        $this->assertEquals($expected, $result);
    }
}
