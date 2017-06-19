<?php 

namespace Tests\Themes;

use Kabas\Themes\Container;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\NotFoundException;

class ContainerTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $this->container = new Container;
    }

    /** @test */
    public function can_set_and_get_current_theme()
    {
        $this->container->setCurrent('TheCapricorn');
        $this->assertSame('TheCapricorn', $this->container->getCurrent()['name']);
    }

    /** @test */
    public function throws_exception_if_theme_does_not_exist()
    {
        $this->expectException(NotFoundException::class);
        $this->container->setCurrent('FooTheme');
    }

}