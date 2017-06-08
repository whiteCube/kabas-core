<?php

namespace Tests\Config;

use Kabas\Config\Settings;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->settings = new Settings;
    }

    /** @test */
    public function can_be_properly_instanciated()
    {
        $this->assertInstanceOf(Settings::class, $this->settings);
    }

    /** @test */
    public function can_retrieve_data()
    {
        $this->assertEquals('root', $this->settings->get('database.username'));
    }

    /** @test */
    public function can_set_data()
    {
        $this->settings->set('database', ['foo' => 'bar']);
        $this->settings->set('database.bar', ['baz']);
        $this->settings->set('database.baz', 'test');
        $this->assertEquals('bar', $this->settings->get('database.foo'));
        $this->assertEquals(['baz'], $this->settings->get('database.bar'));
        $this->assertEquals('test', $this->settings->get('database.baz'));
    }

    /** @test */
    public function can_remove_data()
    {
        $this->settings->set('database', ['foo' => 'bar']);
        $this->settings->remove('database.foo');
        $this->assertTrue(is_null($this->settings->get('database.foo')));
    }

    /** @test */
    public function can_pluck_data()
    {
        $this->settings->set('database', ['foo' => 'bar']);
        $this->assertEquals('bar', $this->settings->pluck('database.foo'));
        $this->assertTrue(is_null($this->settings->get('database.foo')));;
    }
}