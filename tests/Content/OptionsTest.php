<?php

namespace Tests\Content;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Content\Options\Container;

class OptionsTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->visit('/foo/bar');
        $this->container = new Container;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

}