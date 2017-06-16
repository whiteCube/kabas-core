<?php 

namespace Tests\Fields;

use Kabas\Fields\Item;
use Kabas\Fields\Types\Text;
use Kabas\Fields\Types\Select;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class ItemTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = 'Title';
        $data->type = 'text';
        $data->default = 'default value';
        $data->description = 'A simple description of my field';
        $this->item = new Text('test', null, $data);
        $this->item->set('Foo value');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Item::class, $this->item);
    }

    /** @test */
    public function can_format_a_value()
    {
        $this->assertSame('Foo bar', $this->item->format('   Foo bar   '));
    }

    /** @test */
    public function can_return_the_current_field_type()
    {
        $this->assertSame('text', $this->item->getType());
    }

    /** @test */
    public function can_return_the_current_field_name()
    {
        $this->assertSame('test', $this->item->getName());
    }

    /** @test */
    public function can_return_the_field_output()
    {
        $this->assertSame('Foo value', $this->item->get());
    }

    /** @test */
    public function can_return_the_fields_raw_value()
    {
        $this->assertSame('Foo value', $this->item->getValue());
    }

    /** @test */
    public function can_returthe_fields_default_value()
    {
        $this->assertSame('default value', $this->item->getDefault());
    }

    /** @test */
    public function can_return_the_fields_label()
    {
        $this->assertSame('Title', $this->item->getLabel());
    }

    /** @test */
    public function can_return_the_fields_description()
    {
        $this->assertSame('A simple description of my field', $this->item->getDescription());
    }

    /** @test */
    public function can_determine_if_field_is_multiple()
    {
        $this->assertFalse($this->item->isMultiple());
        $data = new \stdClass;
        $data->label = 'Select';
        $data->type = 'select';
        $data->multiple = true;
        $data->options = ['foo' => 'foo', 'bar' => 'bar'];
        $multipleField = new Select('select', null, $data);
        $this->assertTrue($multipleField->isMultiple());
    }

    /** @test */
    public function can_check_its_own_value_and_throw_exception_when_invalid()
    {
        $this->expectException(TypeException::class);
        $this->item->set(['foo']);
    }

}