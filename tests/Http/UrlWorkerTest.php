<?php 

namespace Tests\Http;

use Kabas\Http\Routes\UrlWorker;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class UrlWorkerTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_be_properly_instanciated()
    {
        $this->alterGlobalServer();
        $this->assertInstanceOf(UrlWorker::class, new UrlWorker);
    }

    /** @test */
    public function can_return_information_about_given_url()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/en/foo/bar');
        $this->assertEquals('/foo/bar', $result->route);
        $this->assertEquals('en', $result->lang->slug);
    }

    /** @test */
    public function can_return_info_about_given_url_within_subdirectory()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $_SERVER['SCRIPT_NAME'] = '/sub/index.php';
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/sub/en/foo/bar');
        $this->assertEquals('/foo/bar', $result->route);
        $this->assertEquals('en', $result->lang->slug);
    }

    /** @test */
    public function returns_false_if_unsupported_lang_in_url()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $worker = new UrlWorker;
        $result = $worker->parseUrl('http://www.foo.com/de/foo/bar');
        $this->assertFalse($result->lang);   
    }
}