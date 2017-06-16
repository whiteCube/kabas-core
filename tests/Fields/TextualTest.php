<?php 

namespace Tests\Fields;

use Kabas\Fields\Types\Text;
use PHPUnit\Framework\TestCase;

class TextualTest extends TestCase
{

    public function setUp()
    {
        $data = new \stdClass;
        $data->label = "Title";
        $data->type = "text";
        $this->textfield = new Text('title', $data);
        $this->textfield->set('My foo title');
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->expectOutputString('My foo title');
        echo $this->textfield;
    }

    /** @test */
    public function can_be_uppercased()
    {
        $this->expectOutputString('MY FOO TITLE');
        echo $this->textfield->uppercase();
    }

    /** @test */
    public function can_be_lowercased()
    {
        $this->expectOutputString('my foo title');
        echo $this->textfield->lowercase();
    }

    /** @test */
    public function can_escape_html_entities()
    {
        $this->expectOutputString('&lt;foo&gt;');
        $this->textfield->set('<foo>');
        echo $this->textfield->escape();
    }

    /** @test */
    public function can_check_if_value_contains_a_string()
    {
        $this->assertFalse($this->textfield->contains('Foo'));
        $this->assertTrue($this->textfield->contains('Foo', false));
        $this->assertFalse($this->textfield->contains('bar'));
    }

    /** @test */
    public function can_cut_value_and_append_triple_dots()
    {
        $this->expectOutputString('My foo tit&nbsp;&hellip;');
        echo $this->textfield->cut(10);
    }

    /** @test */
    public function can_cut_value_and_append_triple_dots_without_cutting_into_a_word()
    {
        $this->expectOutputString('My foo&nbsp;&hellip;');
        echo $this->textfield->shorten(10);
    }

    /** @test */
    public function can_check_if_value_is_longer_than_given_length()
    {
        $this->assertTrue($this->textfield->exceeds(5));
        $this->assertFalse($this->textfield->exceeds(100));
    }

}