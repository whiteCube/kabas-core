<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Email;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class EmailTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = "Email";
        $data->type = "email";
        $this->email = new Email('Email', null, $data);
        $this->email->set('hello@whitecube.be');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Email::class, $this->email);
    }

    /** @test */
    public function can_only_accept_valid_email_values()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(TypeException::class);
        $this->email->set('foo');
    }

}