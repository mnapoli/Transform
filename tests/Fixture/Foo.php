<?php

namespace Transform\Test\Fixture;

class Foo
{
    public $public;
    private $private;
    public $excluded = 'Hello';
    private $private2 = 'abc';

    public function getPrivate()
    {
        return $this->private;
    }

    public function setPrivate($private)
    {
        $this->private = $private;
    }

    public function getMethod()
    {
        return 'foo';
    }

    public function setMethod($a)
    {
        // do something
    }

    public function getPrivate2()
    {
        return $this->private2;
    }
}
