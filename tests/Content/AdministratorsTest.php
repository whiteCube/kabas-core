<?php

namespace Tests\Content;

use Kabas\App;
use Kabas\Utils\Session;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Content\Administrators\Item;
use Kabas\Content\Administrators\Container;

class AdministratorsTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $this->container = new Container;
        $this->container->parse();
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    /** @test */
    public function can_create_admin_accounts()
    {
        $this->createUser();
        $file = STORAGE_PATH . DS . 'administrators' . DS . 'Foo.json';
        $this->assertTrue(file_exists($file));
    }

    /** @test */
    public function returns_false_if_account_could_not_be_created()
    {
        $result = $this->container->create([]);
        $this->assertFalse($result);
    }

    /** @test */
    public function can_log_a_user_in()
    {
        $this->createUser();
        $this->login();
        $this->assertTrue($this->container->isAuthenticated());
    }

    /** @test */
    public function returns_false_if_logging_in_with_incorrect_info()
    {
        $this->assertFalse($this->container->login(['Bar', 'baz']));
    }

    /** @test */
    public function can_check_if_a_user_is_authenticated()
    {
        $this->assertFalse($this->container->isAuthenticated());
        $this->createUser();
        $this->login();
        $this->assertTrue($this->container->isAuthenticated());
    }

    /** @test */
    public function can_log_a_user_out()
    {
        $this->createUser();
        $this->login();
        $this->container->logout();
        $this->assertFalse($this->container->isAuthenticated());
    }

    /** @test */
    public function can_return_the_number_of_items()
    {
        $this->assertSame(0, $this->container->count());
        $this->createUser();
        $this->assertSame(1, $this->container->count());
    }

    protected function createUser()
    {
        $this->container->create([
            'username' => 'Foo',
            'password' => 'bar'
        ]);
    }

    protected function login()
    {
        $this->container->login(['Foo', 'bar']);
    }

    public function tearDown()
    {
        $file = STORAGE_PATH . DS . 'administrators' . DS . 'Foo.json';
        if(file_exists($file)) unlink($file);
    }

}