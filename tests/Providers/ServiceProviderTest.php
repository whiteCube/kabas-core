<?php

namespace Tests\Providers;

use Kabas\Session\Manager;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Theme\TheCapricorn\Providers\Package\SomeService;

class ServiceProviderTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
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
