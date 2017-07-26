<?php

namespace Tests\Utils;

use Kabas\Utils\Menu;
use Kabas\Exceptions\NotFoundException;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
        $this->app->content->parse();
    }

    /** @test */
    public function can_get_a_menu_with_get_method()
    {
        $this->expectOutputRegex('/Homepage/');
        Menu::get('main');
    }

    /** @test */
    public function can_get_a_menu_with_static_method()
    {
        $this->expectOutputRegex('/Homepage/');
        Menu::main();
    }

    /** @test */
    public function cannot_render_undefined_menu()
    {
        $this->expectException(NotFoundException::class);
        Menu::foobar();
    }

}