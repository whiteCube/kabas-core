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
        $this->url->set('https://www.kabas.io#test');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Url::class, $this->url);
    }

    /** @test */
    public function can_parse_all_possible_raw_value_cases()
    {
        $cases = [
            // simple page identifier
            'example' => 'http://www.foo.com/en/foo/bar', 
            // page identifier with anchor
            'example#hash' => 'http://www.foo.com/en/foo/bar#hash', 
            // page identifier with composed anchor
            'example#hash-tag' => 'http://www.foo.com/en/foo/bar#hash-tag', 
            // page identifier with single URL parameter
            'params(foo)' => 'http://www.foo.com/en/params/foo', 
            // page identifier with multiple URL parameters
            'optional(foo,bar)' => 'http://www.foo.com/en/optional/foo/bar', 
            // page identifier with multiple URL parameters and unecessary spaces
            'optional(foo, bar)' => 'http://www.foo.com/en/optional/foo/bar', 
            // page identifier with multiple URL parameters and anchor
            'optional(foo,bar)#hash' => 'http://www.foo.com/en/optional/foo/bar#hash',
            // page identifier with target language
            'example[fr-FR]' => 'http://www.foo.com/fr/foo/bar',
            // page identifier with multiple URL parameters and target language
            'optional(foo,bar)[fr-FR]' => 'http://www.foo.com/fr/optional/foo/bar',
            // page identifier with target language and anchor
            'example[fr-FR]#hash' => 'http://www.foo.com/fr/foo/bar#hash',
            // page identifier with multiple URL parameters, target language and anchor
            'optional(foo,bar)[fr-FR]#hash' => 'http://www.foo.com/fr/optional/foo/bar#hash',
            // full URL
            'https://www.kabas.io/some/url?foo=bar' => 'https://www.kabas.io/some/url?foo=bar',
            // full URL with anchor
            'https://www.kabas.io/some/url?foo=bar&bar=foo#anchor' => 'https://www.kabas.io/some/url?foo=bar&bar=foo#anchor'
        ];
        foreach ($cases as $value => $result) {
            $this->url->set($value);
            $this->assertSame($result, $this->url->get());
        }
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