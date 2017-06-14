<?php

namespace Tests\Content;

use Kabas\App;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Content\Partials\Item;
use Kabas\Content\Partials\Container;
use Kabas\Exceptions\NotFoundException;

class PartialsTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->container = new Container;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_load_a_partial_from_a_json_file()
    {
        $this->assertInstanceOf(Item::class, $this->container->load('header'));
    }

    /** @test */
    public function can_load_a_partial_from_a_controller()
    {
        $this->visit('/foo/bar');
        $this->assertInstanceOf(Item::class, $this->container->load('Foo'));
    }

    /** @test */
    public function throws_exception_if_loading_from_controller_without_specifying_template()
    {
        $this->expectException(NotFoundException::class);
        $this->visit('/foo/bar');
        $this->container->load('Bar');
    }

    /** @test */
    public function can_load_a_partial_from_a_view()
    {
        $this->assertInstanceOf(Item::class, $this->container->load('test'));
    }

    /** @test */
    public function throws_exception_if_partial_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->container->load('foobarbaz');
    }

    /** @test */
    public function can_get_a_piece_of_data()
    {
        $partial = $this->container->load('header');
        $this->assertContains('png', $partial->logo->path);
    }

    /** @test */
    public function returns_null_when_trying_to_get_undefined_data()
    {
        $partial = $this->container->load('header');
        $this->assertNull($partial->foo);
    }

    /** @test */
    public function can_set_the_value_of_a_field_within_a_partial()
    {
        $partial = $this->container->load('header');
        $partial->title = 'override';
        $this->assertEquals('override', $partial->title);
    }

}