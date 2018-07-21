<?php 

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Kabas\Http\Query;
use Kabas\Config\LanguageRepository;
use Tests\CreatesApplication;

class QueryTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /**
     * Test case defined locales
     * @var Kabas\Config\LanguageRepository
     */
    protected $locales;

    public function setUp()
    {
        $available = ['en-GB' => ['slug' => 'en', 'label' => 'English']];
        $default = 'en-GB';
        $this->locales = new LanguageRepository($available, $default);
    }

    /** @test */
    public function can_instanciate_with_default_values()
    {
        $query = new Query($this->locales, 'www.foo.com', '/bar/');
        $this->assertEquals('http://www.foo.com/bar', $query->getURL());
    }

    /** @test */
    public function can_set_and_get_secure_scheme()
    {
        $query = new Query($this->locales, 'www.foo.com', '/bar/', null, true);
        $this->assertEquals('https', $query->getScheme());
        $this->assertEquals('https://www.foo.com/bar', $query->getURL());
    }

    /** @test */
    public function can_set_and_get_host()
    {
        $query = new Query($this->locales, 'www.foo.com', '/');
        $query->setHost('bar.com/');
        $this->assertEquals('bar.com', $query->getHost());
        $this->assertEquals('http://bar.com', $query->getURL());
    }

    /** @test */
    public function can_instanciate_with_script_uri_leading_to_subdirectory()
    {
        $query = new Query($this->locales, 'www.foo.com', '/subdirectory/bar/', '/subdirectory/index.php');
        $this->assertEquals('subdirectory', $query->getRoot());
        $this->assertEquals('bar', $query->getURI());
        $this->assertEquals('http://www.foo.com/subdirectory/bar', $query->getURL());
    }

    /** @test */
    public function can_set_and_get_URI_without_locale()
    {
        $query = new Query($this->locales, 'test.com', '/foo/bar/');
        $query->setURI('bar/foo');
        $this->assertEquals('bar/foo', $query->getURI());
        $this->assertEquals('http://test.com/bar/foo', $query->getURL());
    }

    /** @test */
    public function can_set_and_get_URI_with_locale()
    {
        $query = new Query($this->locales, 'test.com', '/foo/bar/');
        $query->setURI('en/bar/foo');
        $this->assertEquals('en/bar/foo', $query->getURI());
        $this->assertEquals('/bar/foo', $query->getRoute());
        $this->assertEquals('en', $query->getLocale());
        $this->assertEquals('http://test.com/en/bar/foo', $query->getURL());
    }

    /** @test */
    public function can_set_uri_with_query_string()
    {
        $query = new Query($this->locales, 'test.com', '/foo/bar?test=true');
        $this->assertEquals('foo/bar', $query->getURI());
        $this->assertEquals('http://test.com/foo/bar', $query->getURL());
    }

    /** @test */
    public function can_get_route_by_setting_localized_URI()
    {
        $query = new Query($this->locales, 'test.com', 'subdirectory/en/foo/bar/', 'subdirectory/index.php');
        $this->assertEquals('en/foo/bar', $query->getURI());
        $this->assertEquals('/foo/bar', $query->getRoute());
    }

    /** @test */
    public function can_set_empty_route_with_slash()
    {
        $query = new Query($this->locales, 'test.com', '/subdirectory/en', 'subdirectory/index.php');
        $this->assertEquals('en', $query->getURI());
        $this->assertEquals('/', $query->getRoute());
    }

    /** @test */
    public function can_instanciate_from_server_values()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $query = Query::createFromServer();
        $this->assertInstanceOf(Query::class, $query);
        $this->assertEquals('http', $query->getScheme());
        $this->assertEquals('www.foo.com', $query->getHost());
        $this->assertNull($query->getRoot());
        $this->assertEquals('', $query->getURI());
        $this->assertNull($query->getLocale());
        $this->assertEquals('/', $query->getRoute());
    }
}