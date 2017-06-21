<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Url;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class UrlTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $data = new \stdClass;
        $data->label = 'Url';
        $data->type = 'url';
        $this->url = new Url('link', null, $data);
        $this->url->set('https://www.kabas.io');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Url::class, $this->url);
    }

    /** @test */
    public function can_determine_if_value_is_local()
    {
        $this->assertFalse($this->url->isLocal());
        $this->url->set('http://www.foo.com/foo');
        $this->assertTrue($this->url->isLocal());
    }

    /** @test */
    public function can_returned_parsed_url()
    {
        $this->url->set('http://www.foo.com/foo');
        $this->assertSame('/foo', $this->url->getParsed()->query);
    }

}