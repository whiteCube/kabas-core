<?php

namespace Tests\Content;

use Kabas\App;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Content\Pages\Item;
use Kabas\Content\Pages\Container;
use Kabas\Exceptions\NotFoundException;

class PagesTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->setPageRoute('/foo/bar');
        $this->app->router->load()->setCurrent();
        $this->container = new Container;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_return_a_page()
    {
        $this->assertInstanceOf(Item::class, $this->container->get('about'));
    }

    /** @test */
    public function can_return_the_current_page()
    {
        $current = $this->container->getCurrent();
        $this->assertInstanceOf(Item::class, $current);
        $this->assertSame('/foo/bar', $current->route);
    }

    /** @test */
    public function can_return_page_for_current_language()
    {
        $lang = ['/fr/a-propos' => 'Page à propos', '/en/about' => 'About page'];
        foreach ($lang as $route => $title) {
            $this->setPageRoute($route);
            $this->app->router->load()->setCurrent();
            $this->container = new Container;
            $this->assertEquals($title, $this->container->getCurrent()->getTitle()->get());
        }
    }

}