<?php

namespace Tests\Utils;

use Kabas\Utils\Auth;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->visit('/foo/bar');
        if(!is_dir(STORAGE_PATH . DS . 'administrators')) mkdir(STORAGE_PATH . DS . 'administrators', 0777, true);
        file_put_contents(STORAGE_PATH . DS . 'administrators' . DS . 'Void.json', json_encode(['password' => password_hash('foobar', PASSWORD_BCRYPT)]));
    }

    /** @test */
    public function can_forward_method_calls_to_administrators_container()
    {
        $this->assertFalse(Auth::login('foo', 'bar'));
    }

    /** @test */
    public function can_check_if_user_is_logged()
    {
        $this->assertFalse(Auth::check());
        Auth::login('Void', 'foobar');
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function can_check_if_admin_accounts_exist()
    {
        $this->assertSame(1, Auth::hasAdministrators());
    }

}