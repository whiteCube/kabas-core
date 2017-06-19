<?php 

namespace Tests\Fields;

use Kabas\Fields\Types\Checkbox;
use PHPUnit\Framework\TestCase;

class SelectableTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = 'Checkbox';
        $data->type = 'checkbox';
        $data->multiple = true;
        $data->options = ['foo' => 'foo', 'bar' => 'bar'];
        $this->selectable = new Checkbox('Checkbox', null, $data);
        $this->selectable->set('foo');
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->expectOutputString('foo');
        echo $this->selectable;
    }

    /** @test */
    public function can_format_its_value()
    {
        $this->assertTrue(is_array($this->selectable->format(['foo' => 'bar', 'bar' => 'baz'])));
        $this->assertSame('true', $this->selectable->format(true));
        $this->assertSame('5', $this->selectable->format(5));
    }

}