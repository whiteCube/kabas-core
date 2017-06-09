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
}
