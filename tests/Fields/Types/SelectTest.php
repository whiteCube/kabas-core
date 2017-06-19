<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Select;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class SelectTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
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
        $this->assertInstanceOf(Select::class, $this->select);
    }

}