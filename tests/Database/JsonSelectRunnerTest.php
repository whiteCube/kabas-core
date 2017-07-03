<?php

namespace Tests\Database;

use Kabas\Utils\Log;
use Tests\CreatesApplication;
use Kabas\Content\Container as Content;
use PHPUnit\Framework\TestCase;

class JsonSelectRunnerTest extends TestCase
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
        Content::setParsed(true);
        if(!defined('THEME_PATH')) define('THEME_PATH', __DIR__);
    }

    /** @test */
    public function can_find_first_item_of_model()
    {
        $this->assertInstanceOf(JsonModel::class, JsonModel::first());
    }

    /** @test */
    public function can_find_all_items_of_model()
    {
        $items = JsonModel::all();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $items);
        $this->assertCount(3, $items);
    }

    /** @test */
    public function can_get_all_and_first_items_from_simple_whereKey_condition()
    {
        $items = JsonModel::whereId(1)->get();
        $item = JsonModel::where('id', '=', 2)->first();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(JsonModel::class, $item);
    }

    /** @test */
    public function can_get_all_and_first_items_from_simple_whereData_condition()
    {
        $items = JsonModel::whereFoo('third')->get();
        $item = JsonModel::where('foo', '=', 'third')->first();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(JsonModel::class, $item);
    }
}