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

    protected function mockModelGetPaths($key)
    {
        return [
            'shared' => __DIR__ . '/../TestTheme/content/shared/testJsonModels/' . $key . '.json',
            'en-GB' => __DIR__ . '/../TestTheme/content/en-GB/testJsonModels/' . $key . '.json',
            'fr-FR' => __DIR__ . '/../TestTheme/content/fr-FR/testJsonModels/' . $key . '.json'
        ];
    }

    protected function mockParsedJsonFile(array $data) {
        $json = new \stdClass();
        $json->data = (object) $data;
        return $json;
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
    public function can_inject_items_and_retrieve_item()
    {
        $model = new JsonModel;
        $items = ['foo' => 'test1', 'bar' => 'test2'];
        Cache::merge($items, $model);
        Cache::inject('test', 'test3', $model);
        $foo = Cache::retrieve('foo', 'jsonModel');
        $bar = Cache::retrieve('bar', $model);
        $test = Cache::retrieve('test', $model);
        $this->assertInstanceOf(Item::class, $foo);
        $this->assertInstanceOf(Item::class, $bar);
        $this->assertInstanceOf(Item::class, $test);
    }

    /** @test */
    public function can_retrieve_all_items_for_space()
    {
        $items = ['foo' => 'test', 'bar' => 'test'];
        Cache::merge($items, new JsonModel);
        $this->assertCount(2, Cache::all('jsonModel'));
    }

    /** @test */
    public function can_get_locale_data_from_retrieved_item()
    {
        $model = new JsonModel;
        Cache::inject('foo', 'bar', $model);
        $this->assertEquals('bar', Cache::retrieve('foo', $model)->getData());
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
        Cache::addEmpty('foo', $this->mockModelGetPaths('bar'), new JsonModel);
        $item = Cache::retrieve('foo', 'jsonModel');
        $this->assertNull($item->data['shared']);
        $this->assertContains('TestTheme/content/shared/testJsonModels/bar.json', $item->paths['shared']);
    }

    protected function addMultipleEmptyItems()
    {
        $items = [
            'test' => $this->mockModelGetPaths('test'),
            'foo' => $this->mockModelGetPaths('bar')
        ];
        Cache::addEmpties($items, new JsonModel);
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
        $this->assertEquals('bar', Cache::retrieve('foo', 'jsonModel')->getData()->data->foo);
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

    /** @test */
    public function can_merge_shared_properties_into_locale()
    {
        $model = new JsonModel;
        Cache::inject('foo', $this->mockParsedJsonFile(['bar' => 'test', 'test' => 'content']), $model, 'shared');
        Cache::inject('foo', $this->mockParsedJsonFile(['bar' => 'foo', 'foo' => 'bar']), $model, 'en-GB');
        $foo = Cache::retrieve('foo', $model)->toDataObject('id');
        $this->assertEquals('foo', $foo->data->bar);
        $this->assertEquals('content', $foo->data->test);
        $this->assertEquals('bar', $foo->data->foo);
    }
}
