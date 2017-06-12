<?php

namespace Tests\Model;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
    
define('THEME_PATH', __DIR__);

class JsonDriverTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
    }

    /** @test */
    public function can_get_all_models_from_JsonDriver()
    {
        $models = TestJsonModel::all();
        $this->assertInstanceOf(TestJsonModel::class, $models[0]);
    }

    /** @test */
    public function can_find_single_model_from_JsonDriver()
    {
        $models = TestJsonModel::find('test');
        $this->assertInstanceOf(TestJsonModel::class, $models[0]);
    }

    /** @test */
    public function can_return_null_if_single_model_not_found_from_JsonDriver()
    {
        $models = TestJsonModel::find('foo');
        $this->assertNull($models);
    }

    /** @test */
    public function can_find_multiple_models_from_JsonDriver()
    {
        $models = TestJsonModel::find(['test', 'foo', 'bar']);
        $this->assertCount(2, $models);
        $this->assertInstanceOf(TestJsonModel::class, $models[0]);
    }

    /** @test */
    public function can_return_null_if_multiple_models_not_found_from_JsonDriver()
    {
        $models = TestJsonModel::find(['foo', 'oof']);
        $this->assertNull($models);
    }
}
