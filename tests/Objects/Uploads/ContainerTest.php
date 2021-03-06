<?php

namespace Tests\Objects\Uploads;

use Kabas\Objects\Uploads\Item;
use PHPUnit\Framework\TestCase;
use Kabas\Objects\Uploads\Container;

class ContainerTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('PUBLIC_PATH')) define('PUBLIC_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS . 'TestTheme' . DS . 'public'));
        $_FILES = [
            'foo' => [
                'size' => 424242,
                'name' => 'Foo.png'
            ]
        ];

        $this->container = new Container();
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_check_if_upload_exists()
    {
        $this->assertTrue($this->container->has('foo'));
    }

    /** @test */
    public function can_get_an_upload_item()
    {
        $item = $this->container->get('foo');
        $this->assertInstanceOf(Item::class, $item);
        $this->assertSame(424242, $item->size);
    }

    /** @test */
    public function can_use_accessor_to_get_item()
    {
        $item = $this->container->foo();
        $this->assertInstanceOf(Item::class, $item);
        $this->assertSame('Foo.png', $item->name);
    }

}