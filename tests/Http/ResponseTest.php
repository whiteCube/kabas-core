<?php 

namespace Tests\Http;

use Kabas\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    /** @test */
    public function can_be_properly_instanciated()
    {
        $resp = new Response;
        $this->assertInstanceOf(Response::class, $resp);
    }

    /** @test */
    public function throws_exception_if_no_response_defined()
    {
        $resp = new Response;
        $this->expectException(\Exception::class);
        $resp->send(null);
    }
}