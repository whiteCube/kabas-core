<?php

namespace Tests\Providers;

use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Tests\RunsCommands;
use Theme\TheCapricorn\Providers\Package\SomeService;
use Theme\TheCapricorn\Providers\Package\SomeServiceProvider;

class ServiceProviderTest extends TestCase
{
    use CreatesApplication;
    use RunsCommands;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->prepareCommands();
        $this->createApplication();
        $this->app->config->set('app.providers', [SomeServiceProvider::class]);
        $this->visit('/fr');
    }

    /** @test */
    public function can_load_a_service()
    {
        $this->assertInstanceOf(SomeService::class, $this->app->someservice);
    }

    public function can_load_routes()
    {

    }

    public function can_publish_a_config_file()
    {
        
    }

}
