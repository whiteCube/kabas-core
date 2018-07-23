<?php

namespace Tests\Providers;

use Kabas\Providers\ServiceProvider;
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

    /** @test */
    public function can_load_a_service()
    {
        if(!defined('THEME')) define('THEME', 'TheCapricorn');
        $this->prepareCommands();
        $this->visit('/en/about', null, [SomeServiceProvider::class]);
        $this->assertInstanceOf(SomeService::class, $this->app->someservice);
    }

    /** @test */
    public function always_has_boot_and_register_methods()
    {
        $provider = new ServiceProvider($this->app);
        $this->assertNull($provider->register());
        $this->assertNull($provider->boot());
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
