<?php 

namespace Tests\Http;

use Kabas\Http\Responses\Json;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_be_properly_instanciated()
    {
        $resp = new Json(['foo' => 'bar']);
        $this->assertInstanceOf(Json::class, $resp);
    }

    /** @test */
    public function can_set_headers()
    {
        $this->expectOutputRegex('/^\{[^}]+}/');
        $resp = new Json(['foo' => 'bar']);
        $resp->headers(['Content-type:application/test']);
        $resp->run();
        $this->assertContains('Content-type:application/test', $resp->getHeaders());
    }

    /** @test */
    public function can_output_pretty_printed_json()
    {
        $this->expectOutputRegex('/^\{\n[^}]+\n}/');
        $resp = new Json(['foo' => 'bar']);
        $resp->pretty();
        $resp->run();
    }

}