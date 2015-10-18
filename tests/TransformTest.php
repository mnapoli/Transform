<?php

namespace Transform\Test;

use Transform\Test\Fixture\A;
use Transform\Test\Fixture\B;
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
        $transformer->addMapping([
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
                    'private2' => [
                        'get' => function () {
                            return $this->private2;
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
            'private2' => 'abc',
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
            'private2' => 42,
        ];

        $transformer = new Transformer;
        $transformer->addMapping([
            Foo::class => [
                'fields' => [
                    'public',
                    'private' => [],
                    'method' => [
                        'get' => null,
                        'set' => 'setMethod()',
                    ],
                    'private2' => [
                        'get' => null,
                        'set' => function ($value) {
                            // We are in the object's scope
                            $this->private2 = $value;
                        },
                    ],
                ],
            ],
        ]);

        /** @var Foo $object */
        $object = $transformer->transform($data, new Foo);

        $this->assertInstanceOf(Foo::class, $object);
        $this->assertEquals('public', $object->public);
        $this->assertEquals('private', $object->getPrivate());
        $this->assertEquals(42, $object->getPrivate2());
    }

    /**
     * @test
     */
    public function transform_sub_objects()
    {
        $data = [
            'b' => [
                'foo' => 'bar',
            ],
        ];

        $transformer = new Transformer;
        $transformer->addMapping([
            A::class => [
                'fields' => [
                    'b' => [
                        'type' => B::class,
                    ],
                ],
            ],
            B::class => [
                'fields' => [
                    'foo',
                ],
            ],
        ]);

        // From array to object
        /** @var A $a */
        $a = $transformer->transform($data, new A);
        $this->assertInstanceOf(A::class, $a);
        $this->assertInstanceOf(B::class, $a->b);
        $this->assertEquals('bar', $a->b->foo);

        // From object to array
        $newData = $transformer->transform($a, 'array');
        $this->assertEquals($data, $newData);
    }
}
