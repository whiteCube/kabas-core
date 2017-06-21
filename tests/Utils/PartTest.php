<?php

namespace Tests;

use Kabas\Utils\Part;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class PartTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
        $this->app->loadAliases();
    }

    /** @test */
    public function can_get_a_part_and_render_it()
    {
        $this->expectOutputRegex('/An incredible test page/');
        Part::get('header');
    }

    /** @test */
    public function can_use_magic_methods_to_render_a_part()
    {
        $this->expectOutputRegex('/An incredible test page/');
        Part::header();
    }

}