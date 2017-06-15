<?php

namespace Tests;

use Kabas\Utils\Page;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
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
    public function can_return_the_current_page_title()
    {
        $this->assertSame('Example page', Page::title());
    }

    /** @test */
    public function can_return_the_current_page_id()
    {
        $this->assertSame('example', Page::id());
    }

    /** @test */
    public function can_return_the_current_page_template_name()
    {
        $this->assertSame('example', Page::template());
    }

}