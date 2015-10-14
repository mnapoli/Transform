<?php

namespace Transform\Test\Fixture;

class Foo
{
    public $public;
    private $private;
    public $excluded = 'Hello';

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
}
