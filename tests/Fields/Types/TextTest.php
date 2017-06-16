<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Text;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class TextTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = "Title";
        $data->type = "text";
        $this->textfield = new Text('title', $data);
        $this->textfield->set('My foo title');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Text::class, $this->textfield);
    }

    /** @test */
    public function returns_the_proper_type()
    {
        $this->assertSame('text', $this->textfield->getType());
    }

    /** @test */
    public function can_only_contain_string_values()
    {
        $this->textfield->set('hello');
        $this->assertSame('hello', $this->textfield->getValue());
        $this->expectException(TypeException::class);
        $this->textfield->set(['foo']);
    }

}