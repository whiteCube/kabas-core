<?php 

namespace Tests\Fields;

use Kabas\Fields\Container;
use Kabas\Fields\Types\Text;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class ContainerTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $this->container = new Container;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_make_a_field_instance()
    {
        $data = new \stdClass;
        $data->label = "Title";
        $data->type = "text";
        $field = $this->container->make('title', $data);
        $this->assertInstanceOf(Text::class, $field);
    }

    /** @test */
    public function throws_exception_if_making_field_type_that_does_not_exist()
    {
        $this->expectException(TypeException::class);
        $data = new \stdClass;
        $data->label = "Foo";
        $data->type = "foo";
        $this->container->make('foo', $data);
    }

    /** @test */
    public function can_return_a_list_of_supported_field_types()
    {
        $supported = $this->container->getSupported();
        $this->assertTrue(is_array($supported));
        $this->assertArrayHasKey('text', $supported);
        $this->assertArrayHasKey('checkbox', $supported);
    }

}