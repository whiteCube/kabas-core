<?php 

namespace Tests\Http;

use Kabas\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    /** @test */
    public function can_be_properly_instanciated()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $req = new Request;
        $this->assertInstanceOf(Request::class, $req);
    }

    /** @test */
    public function can_check_if_request_is_post()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $req = new Request;
        $this->assertTrue($req->isPost());
        $this->assertFalse($req->isGet());
    }

    /** @test */
    public function can_check_if_request_is_get()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $req = new Request;
        $this->assertTrue($req->isGet());
        $this->assertFalse($req->isPost());
    }

    /** @test */
    public function can_return_the_request_method()
    {
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $req = new Request;
        $this->assertEquals('PATCH', $req->method());
    }
}