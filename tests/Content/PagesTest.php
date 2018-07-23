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

    protected function boot()
    {
        $this->createApplication(null, '/foo/bar');
        $this->app->router->load()->setCurrent();
        $this->container = new Container;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->boot();
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_return_a_page()
    {
        $this->boot();
        $this->assertInstanceOf(Item::class, $this->container->get('about'));
    }

    /** @test */
    public function can_return_the_current_page()
    {
        $this->boot();
        $current = $this->container->getCurrent();
        $this->assertInstanceOf(Item::class, $current);
        $this->assertSame('/foo/bar', $current->route);
    }

    /** @test */
    public function can_return_page_for_current_language()
    {
        $this->createApplication(null, '/fr/a-propos');
        $this->app->router->load()->setCurrent();
        $this->container = new Container;
        $this->assertEquals('Page à propos', $this->container->getCurrent()->getTitle()->get());
    }

}