<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Checkbox;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class CheckboxTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = 'Checkbox';
        $data->type = 'checkbox';
        $data->multiple = true;
        $data->options = ['foo' => 'foo', 'bar' => 'bar'];
        $this->checkbox = new Checkbox('Checkbox', null, $data);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Checkbox::class, $this->checkbox);
    }

    /** @test */
    public function returns_the_proper_type()
    {
        $this->assertSame('checkbox', $this->checkbox->getType());
    }


}