<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Wysiwyg;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class WysiwygTest extends TestCase
{

    public function setUp()
    {
        $data = new \stdClass;
        $data->label = "Main text";
        $data->type = "wysiwyg";
        $this->textfield = new Wysiwyg('title', $data);
        $this->textfield->set('<h1>My foo title</h1>');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Wysiwyg::class, $this->textfield);
    }

    /** @test */
    public function can_format_headings()
    {
        $this->textfield->headingLevel(3);
        $this->assertSame('<h3>My foo title</h3>', $this->textfield->get());
    }

}