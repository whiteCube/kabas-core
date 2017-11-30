<?php

namespace Tests\Utils;

use Kabas\Utils\Url;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\NotFoundException;

class UrlTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createMinimalContentApplicationForRoute('/foo/bar');
    }

    /** @test */
    public function can_return_url_to_desired_page()
    {
        $this->assertSame('http://www.foo.com/en/about', Url::to('about'));
        $this->assertSame('http://www.foo.com/en/params/foo', Url::to('params', ['param' => 'foo']));
    }

    /** @test */
    public function throws_an_exception_if_linking_to_route_without_its_parameter()
    {
        $this->expectException(\Exception::class);
        Url::to('params', ['foo' => 'bar']);
    }

    /** @test */
    public function throws_exception_if_linking_to_page_that_does_not_exist()
    {
        $this->expectException(NotFoundException::class);
        Url::to('test');
    }

    /** @test */
    public function can_return_the_current_url()
    {
        $this->assertSame('http://www.foo.com/en/foo/bar', Url::getCurrent());
    }

    /** @test */
    public function can_return_the_current_url_in_another_lang()
    {
        $this->assertSame('http://www.foo.com/fr/foo/bar', Url::lang('fr'));
    }

    /** @test */
    public function can_return_the_base_url_for_the_site()
    {
        $this->assertSame('http://www.foo.com', Url::base());
    }

    /** @test */
    public function can_return_the_url_to_an_asset()
    {
        $this->assertContains('http://www.foo.com/TheCapricorn/foo.css', Url::asset('foo.css'));
    }

    /** @test */
    public function can_return_an_url_from_a_path()
    {
        $this->assertEquals('http://www.foo.com/TheCapricorn/foo.png', Url::fromPath(PUBLIC_PATH . '/TheCapricorn/foo.png'));
    }

    /** @test */
    public function can_parse_an_url_and_return_an_object()
    {
        $parsed = Url::parse('http://www.foo.com/en/test');
        $this->assertSame('en', $parsed->lang->slug);
        $this->assertSame('/test', $parsed->route);
        $this->assertSame('http://www.foo.com', $parsed->root);
    }

    /** @test */
    public function can_return_a_route_from_an_url()
    {
        $this->assertSame('/foo/bar', Url::route('http://www.foo.com/foo/bar'));
    }

}