<?php 

namespace Tests\Database;

use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Kabas\Database\Json\Cache\Facade as Cache;
use Kabas\Database\Json\Cache\Container;
use Kabas\Database\Json\Cache\Space;
use Kabas\Database\Json\Cache\Item;

class JsonCacheTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        if(!defined('THEME_PATH')) define('THEME_PATH', __DIR__);
    }

    /** @test */
    public function can_be_used_as_facade_and_make_a_proper_Space_instance()
    {
        Cache::registerSpace(new JsonModel);
        $this->assertInstanceOf(Container::class, Cache::getInstance());
        $this->assertInstanceOf(Space::class, Cache::getSpace('jsonModel'));
    }

    /** @test */
    public function can_retrieve_Space_from_name_and_ModelInterface()
    {
        $modelInterface = new JsonModel;
        Cache::registerSpace($modelInterface);
        $this->assertInstanceOf(Space::class, Cache::getSpace('jsonModel'));
        $this->assertInstanceOf(Space::class, Cache::getSpace($modelInterface));
    }

    /** @test */
    public function can_inject_single_item_and_retrieve_it()
    {
        Cache::inject('foo', 'bar', new JsonModel);
        $this->assertInstanceOf(Item::class, Cache::retrieve('foo', 'jsonModel'));
    }

    /** @test */
    public function can_inject_multiple_items_and_retrieve_item()
    {
        $items = ['foo' => 'test', 'bar' => 'test'];
        Cache::merge($items, new JsonModel);
        $this->assertEquals('test', Cache::retrieve('foo', 'jsonModel')->data);
        $this->assertEquals('test', Cache::retrieve('bar', 'jsonModel')->data);
    }

    /** @test */
    public function can_retrieve_all_items_for_space()
    {
        $items = ['foo' => 'test', 'bar' => 'test'];
        Cache::merge($items, new JsonModel);
        $this->assertCount(2, Cache::all('jsonModel'));
    }
}
