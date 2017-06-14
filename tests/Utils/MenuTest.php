<?php

namespace Tests;

use Kabas\App;
use Kabas\Utils\Menu;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
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

}