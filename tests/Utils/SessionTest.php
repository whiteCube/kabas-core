<?php

namespace Tests\Utils;

use Kabas\App;
use Kabas\Utils\Session;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_forward_method_calls_to_application_session_manager()
    {
        $this->createApplication([
            'session' => \Kabas\Session\Manager::class,
            'config' => \Kabas\Config\Container::class
        ]);
        Session::set('test', 'value');
        $this->assertEquals('value', Session::get('test'));
    }

}