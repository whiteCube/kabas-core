<?php 

namespace Tests\Fields;

use Kabas\Fields\Option;
use Kabas\Fields\Types\Select;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{

    public function setUp()
    {
        $data = new \stdClass;
        $data->label = 'Select';
        $data->type = 'select';
        $data->multiple = false;
        $data->options = ['foo' => 'foo', 'bar' => 'bar'];
        $this->select = new Select('select', null, $data);
        $this->select->set('foo');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Option::class, $this->select->get('foo'));
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->expectOutputString('foo');
        echo $this->select->get('foo');
    }

}