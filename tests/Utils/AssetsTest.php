<?php

namespace Tests;

use Kabas\Utils\Assets;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class AssetsTest extends TestCase
{
    use CreatesApplication;

    public $themeName;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'themes' => \Kabas\Themes\Container::class,
            'router' => \Kabas\Http\Router::class,
        ]);
        $this->themeName = $this->app->config->get('site.theme');
    }

    /** @test */
    public function can_add_a_local_asset_onto_a_page()
    {
        $buffer = '<meta name="kabas-assets-location" value="foo">';
        Assets::add('index.js', 'foo');
        Assets::add('foo.css', 'foo');
        Assets::add('foo.png', 'foo');
        $loaded = Assets::load($buffer);
        $this->assertContains('<script type="text/javascript" src="http://www.foo.com/' . $this->themeName . '/index.js"></script>', $loaded);
        $this->assertContains('<link rel="stylesheet" type="text/css" href="http://www.foo.com/' . $this->themeName . '/foo.css" />', $loaded);
        $this->assertContains('<link rel="icon" href="http://www.foo.com/' . $this->themeName . '/foo.png" />', $loaded);
    }

    /** @test */
    public function can_add_an_external_asset_onto_a_page()
    {
        $buffer = '<meta name="kabas-assets-location" value="foo">';
        Assets::add('https://code.jquery.com/jquery-1.12.4.min.js', 'foo');
        $this->assertEquals('<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>' . PHP_EOL, Assets::load($buffer));
    }

    /** @test */
    public function can_add_an_array_of_assets_at_once()
    {
        $buffer = '<meta name="kabas-assets-location" value="foo">';
        Assets::add(['index.js', 'foo.js'], 'foo');
        $loaded = Assets::load($buffer);
        $this->assertContains('http://www.foo.com/' . $this->themeName . '/index.js', $loaded);
        $this->assertContains('http://www.foo.com/' . $this->themeName . '/foo.js', $loaded);
    }

    /** @test */
    public function can_mark_a_location_for_loading_assets()
    {
        $this->expectOutputString('<meta name="kabas-assets-location" value="foo">' . PHP_EOL);
        Assets::here('foo');
    }

    /** @test */
    public function can_mark_a_location_and_add_asset_at_once()
    {
        $buffer = $this->catch(function(){
            Assets::here('foo', 'index.js');
        });
        $this->assertContains('http://www.foo.com/' . $this->themeName . '/index.js', Assets::load($buffer));
    }

    /** @test */
    public function can_specify_asset_attributes()
    {
        $buffer = '<meta name="kabas-assets-location" value="foo">';
        Assets::add('index.js|async', 'foo');
        $loaded = Assets::load($buffer);
        $this->assertContains('async', $loaded);
        $this->assertContains('http://www.foo.com/' . $this->themeName . '/index.js', $loaded);
    }

    /** @test */
    public function can_add_an_asset_and_be_explicit_about_its_type()
    {
        $buffer = '<meta name="kabas-assets-location" value="foo">';
        Assets::add('index.js', 'foo', 'css');
        $loaded = Assets::load($buffer);
        $this->assertContains('<link rel="stylesheet" type="text/css" href="http://www.foo.com/' . $this->themeName . '/index.js" />', $loaded);
    }

}