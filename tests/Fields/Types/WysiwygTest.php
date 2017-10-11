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
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Wysiwyg::class, $this->textfield);
    }

    /** @test */
    public function can_convert_all_types_of_EOL_to_br()
    {
        $this->textfield->set("A\nB\\nC\rD\\rE\r\nF\\r\\nG\n\rH\\n\\rI");
        $this->assertContains('A<br />', $this->textfield->get());
        $this->assertContains('B<br />', $this->textfield->get());
        $this->assertContains('C<br />', $this->textfield->get());
        $this->assertContains('D<br />', $this->textfield->get());
        $this->assertContains('E<br />', $this->textfield->get());
        $this->assertContains('F<br />', $this->textfield->get());
        $this->assertContains('G<br />', $this->textfield->get());
        $this->assertContains('H<br />', $this->textfield->get());
    }

    /** @test */
    public function can_format_headings()
    {
        $this->textfield->set('# My foo title');
        $this->textfield->headingLevel(3);
        $this->assertSame('<h3>My foo title</h3>', $this->textfield->get());
    }

    /** @test */
    public function can_convert_inline_table()
    {
        $this->textfield->set("| Foo | Bar |\n\r| ---- | ---- |\n\r| Test | Test |");
        $this->assertContains('<table>', $this->textfield->get());
    }

}