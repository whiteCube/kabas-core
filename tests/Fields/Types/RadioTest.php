<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Radio;
use PHPUnit\Framework\TestCase;

class RadioTest extends TestCase
{

    public function setUp()
    {
        $data = new \stdClass;
        $data->label = 'Radio';
        $data->type = 'radio';
        $data->multiple = true;
        $data->options = ['foo' => 'foo', 'bar' => 'bar'];
        $this->radio = new Radio('Radio', null, $data);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Radio::class, $this->radio);
    }
    
}