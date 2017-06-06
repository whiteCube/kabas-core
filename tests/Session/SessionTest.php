<?php

namespace Tests\Session;

use Kabas\Session\Manager;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    public function setUp()
    {
        $this->session = new Manager(new HandlerMock());
    }

    /** @test */
    public function can_initialize_sessions()
    {
        $this->assertInstanceOf(Manager::class, $this->session);
    }

    /** @test */
    public function can_store_and_return_value()
    {
        $this->session->set('test', 'value');
        $this->assertEquals('value', $this->session->get('test'));
    }

    /** @test */
    public function can_return_null_when_key_is_undefined()
    {
        $this->assertNull($this->session->get('test-null'));
    }

    /** @test */
    public function can_check_if_key_exists()
    {
        $this->session->set('test', 'value');
        $this->assertTrue($this->session->has('test'));
        $this->assertFalse($this->session->has('value'));
    }

    /** @test */
    public function can_flush_all_data()
    {
        $this->session->set('test', 'value');
        $this->session->flush();
        $this->assertFalse($this->session->has('test'));
    }

    /** @test */
    public function can_save_all_data()
    {
        $this->session->set('test', 'value');
        $this->session->save();
        $this->assertArrayHasKey('test', HandlerMock::$session['data']);
    }

    /** @test */
    public function cannot_set_with_non_string_key()
    {
        $this->expectException(\TypeError::class);
        $this->session->set(['foo','bar'], 2);
    }

    /** @test */
    public function can_forget_data()
    {
        $this->session->set('test', 'value');
        $this->session->set('foo', 'bar');
        $this->session->forget('test');
        $this->assertFalse($this->session->has('test'));
        $this->assertTrue($this->session->has('foo'));
    }

}
