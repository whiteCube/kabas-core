<?php

namespace Tests\Config;

use Kabas\App;
use Kabas\Config\Container;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
    }

    /** @test */
    public function throws_error_if_method_does_not_exist_in_settings_tree()
    {
        $this->expectException(\Exception::class);
        $this->app->config->test();
    }
}