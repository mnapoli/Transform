<?php

namespace Transform\Test;

use Transform\Test\Fixture\Foo;
use Transform\Transformer;

class TransformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function transform_from_object_to_array()
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

        $result = $transformer->transform($object, 'array');

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function transform_from_array_to_object()
    {
        $data = [
            'public' => 'public',
            'private' => 'private',
            'method' => 'foo',
            'virtual' => 42,
        ];

        $transformer = new Transformer;
        $transformer->addConfiguration([
            Foo::class => [
                'fields' => [
                    'public',
                    'private' => [],
                    'method' => [
                        'get' => null,
                        'set' => 'setMethod()',
                    ],
                    'virtual' => [
                        'get' => null,
                        'set' => function () {
                            // do something with the object
                        },
                    ],
                ],
            ],
        ]);

        $object = $transformer->transform($data, new Foo);

        $this->assertInstanceOf(Foo::class, $object);
    }
}
