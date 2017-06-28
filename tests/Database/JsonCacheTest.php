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
        Cache::inject('test','content', new JsonModel);
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

    /** @test */
    public function can_check_existence_of_existing_and_filled_space()
    {
        $items = ['foo' => 'test', 'bar' => 'test'];
        Cache::merge($items, new JsonModel);
        $this->assertTrue(Cache::has('jsonModel'));
    }

    /** @test */
    public function can_check_inexistance_of_existing_and_empty_space()
    {
        Cache::registerSpace(new JsonModel);
        $this->assertFalse(Cache::has('jsonModel'));
    }

    /** @test */
    public function can_check_inexistance_of_inexisting_space()
    {
        $this->assertFalse(Cache::has('foo'));
    }

    /** @test */
    public function can_add_empty_item_to_space()
    {
        $path = realpath('../TestTheme/content/en-GB/testJsonModels/bar.json');
        Cache::addEmpty('foo', $path, new JsonModel, 'en-GB');
        $item = Cache::retrieve('foo', 'jsonModel', 'en-GB');
        $this->assertNull($item->data);
        $this->assertEquals($path, $item->path);
    }

    protected function addMultipleEmptyItems()
    {
        $items = [
            'test' => realpath(__DIR__ . '/../TestTheme/content/en-GB/testJsonModels/test.json'),
            'foo' => realpath(__DIR__ . '/../TestTheme/content/en-GB/testJsonModels/bar.json')
        ];
        Cache::addEmpties($items, new JsonModel, 'en-GB');
    }

    /** @test */
    public function can_add_multiple_empty_items_to_space()
    {
        $this->addMultipleEmptyItems();
        $this->assertCount(2, Cache::all('jsonModel'));
    }

    /** @test */
    public function can_load_previously_set_empty_items()
    {
        $this->addMultipleEmptyItems();
        Cache::loadEmpties('jsonModel');
        $this->assertEquals('bar', Cache::retrieve('foo', 'jsonModel')->data->data->foo);
    }

    /** @test */
    public function does_not_throw_error_when_trying_to_load_unexisting_empty_items()
    {
        $this->assertNull(Cache::loadEmpties('foo'));
    }

    /** @test */
    public function can_convert_cached_item_to_stdClass()
    {
        Cache::inject('foo', 'bar', new JsonModel);
        $object = Cache::retrieve('foo', 'jsonModel')->toDataObject('id');
        $this->assertInstanceOf(\stdClass::class, $object);
        $this->assertEquals('foo', $object->id);
        $this->assertEquals('bar', $object->value);
    }
}
