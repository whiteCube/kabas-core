<?php

namespace Tests\Providers;

use Kabas\Providers\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Theme\TheCapricorn\Providers\Package\SomeService;
use Theme\TheCapricorn\Providers\Package\SomeServiceProvider;

class ServiceProviderTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_load_a_service()
    {
        $this->createApplication();
        $this->app->config->set('app.providers', [SomeServiceProvider::class]);
        $this->visit('/fr');
        $this->assertInstanceOf(SomeService::class, $this->app->someservice);
    }

    public function can_load_routes()
    {

    }

    /** @test */
    public function can_set_a_config_file_to_be_published()
    {
        $provider = new ServiceProvider($this->app);
        $provider->publishConfig(__dir__ . '/config.php', 'packageconfig');
        $this->assertArrayHasKey('packageconfig', $provider->getConfigs());
    }

}
