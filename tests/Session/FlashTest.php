<?php

namespace Tests\Session;

use Kabas\Session\Manager;
use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{

    public function setUp()
    {
        $this->session = new Manager(new HandlerMock());
    }

    /** @test */
    public function can_store_and_return_value()
    {
        $this->session->flash('test', 'value');
        $this->session->flash()->set('foo', 'bar');
        $this->assertEquals('value', $this->session->flash()->get('test'));
        $this->assertEquals('bar', $this->session->flash()->get('foo'));
    }

    /** @test */
    public function can_return_null_when_key_is_undefined()
    {
        $this->assertNull($this->session->flash()->get('test-null'));
    }

    /** @test */
    public function can_check_if_key_exists()
    {
        $this->session->flash()->set('test', 'value');
        $this->assertTrue($this->session->flash()->has('test'));
        $this->assertFalse($this->session->flash()->has('value'));
    }

    /** @test */
    public function can_pull_data()
    {
        $this->session->flash()->set('foo', 'bar');
        $this->assertEquals('bar', $this->session->flash()->pull('foo'));
        $this->assertFalse($this->session->flash()->has('foo'));
    }

    /** @test */
    public function can_flush_all_data()
    {
        $this->session->flash()->set('test', 'value');
        $this->session->flash()->flush();
        $this->assertFalse($this->session->flash()->has('test'));
    }

    /** @test */
    public function can_save_all_data()
    {
        $this->session->flash()->set('test', 'value');
        $this->session->save();
        $this->assertArrayHasKey('test', HandlerMock::$session['flash']);
    }

    /** @test */
    public function cannot_set_with_non_string_key()
    {
        $this->expectException(\TypeError::class);
        $this->session->flash()->set(['foo','bar'], 2);
    }

    /** @test */
    public function can_forget_data()
    {
        $this->session->flash()->set('test', 'value');
        $this->session->flash()->set('foo', 'bar');
        $this->session->flash()->forget('test');
        $this->assertFalse($this->session->flash()->has('test'));
        $this->assertTrue($this->session->flash()->has('foo'));
    }

    /** @test */
    public function can_retrieve_old_data()
    {
        HandlerMock::$session['flash'] = ['foo' => 'bar'];
        $this->session = new Manager(new HandlerMock());
        $this->assertTrue($this->session->flash()->has('foo'));
        $this->assertFalse(array_key_exists('foo', $this->session->flash()->extract()));
    }

    /** @test */
    public function can_flash_old_key_again()
    {
        HandlerMock::$session['flash'] = ['foo' => 'bar'];
        $this->session = new Manager(new HandlerMock());
        $this->session->flash()->again('foo');
        $this->assertTrue(array_key_exists('foo', $this->session->flash()->extract()));
    }

    /** @test */
    public function can_reflash_all_old_data()
    {
        HandlerMock::$session['flash'] = ['foo' => 'bar'];
        $this->session = new Manager(new HandlerMock());
        $this->session->flash()->set('bar', 'foo');
        $this->session->flash()->reflash();
        $this->assertTrue(array_key_exists('bar', $this->session->flash()->extract()));
        $this->assertTrue(array_key_exists('foo', $this->session->flash()->extract()));
    }

}
