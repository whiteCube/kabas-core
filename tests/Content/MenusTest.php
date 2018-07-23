<?php

namespace Tests\Content;

use Kabas\App;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Content\Menus\Container;
use Kabas\Exceptions\NotFoundException;

class MenusTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->createApplication(null, '/foo/bar');
        $this->container = new Container;
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_return_menu_for_current_language()
    {
        $lang = ['fr' => 'Navigation principale', 'en' => 'Main navigation'];
        $this->createApplication(null, '/fr/foo/bar');
        $this->container = new Container;
        $this->assertSame('Navigation principale', $this->container->get('main')->getTitle()->get());
    }

}