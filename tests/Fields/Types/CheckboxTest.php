<?php 

namespace Tests\Fields\Types;

use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Checkbox;

class CheckboxTest extends TestCase
{

    public function setUp()
    {
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
        $this->assertSame('Checkbox', $this->checkbox->getType());
    }


}