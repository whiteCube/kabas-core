<?php 

namespace Tests\Http;

use Kabas\Http\UrlWorker;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class UrlWorkerTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
    }

    /** @test */
    public function can_be_properly_instanciated()
    {
        $this->assertInstanceOf(UrlWorker::class, new UrlWorker);
    }

    /** @test */
    public function can_return_information_about_given_url()
    {
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/en/foo/bar');
        $this->assertEquals('/foo/bar', $result->route);
        $this->assertEquals('en', $result->lang->slug);
    }

    /** @test */
    public function can_return_info_about_given_url_within_subdirectory()
    {
        $_SERVER['SCRIPT_NAME'] = '/sub/index.php';
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/sub/en/foo/bar');
        $this->assertEquals('/foo/bar', $result->route);
        $this->assertEquals('en', $result->lang->slug);
    }

    /** @test */
    public function returns_false_if_unsupported_lang_in_url()
    {
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/de/foo/bar');
        $this->assertFalse($result->lang);   
    }
}