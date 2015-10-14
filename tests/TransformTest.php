<?php

namespace Transform\Test;

use Transform\Test\Fixture\Foo;
use Transform\Transformer;

class TransformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function transform_an_object()
    {
        $object = new Foo();
        $object->public = 'public';
        $object->setPrivate('private');

        $transformer = new Transformer;
        $transformer->addConfiguration([
            Foo::class => [
                'fields' => [
                    'public',
                    'private' => [],
                    'method' => [
                        'get' => 'getMethod()',
                        'set' => null,
                    ],
                    'virtual' => [
                        'get' => function () {
                            return 42;
                        },
                        'set' => null,
                    ],
                ],
            ],
        ]);

        $expected = [
            'public' => 'public',
            'private' => 'private',
            'method' => 'foo',
            'virtual' => 42,
        ];

        $result = $transformer->transform($object);

        $this->assertEquals($expected, $result);
    }
}
