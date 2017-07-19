<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Tel;
use PHPUnit\Framework\TestCase;

class TelTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS ));
        $data = new \stdClass;
        $data->label = 'Phone';
        $data->type = 'tel';
        $this->tel = new Tel('Phone', null, $data);
        $this->tel->set((object) ['label' => '0400/00.00.00', 'href' => '0400 00 00 00']);
    }

    /** @test */
    public function can_be_cast_as_a_string()
    {
        $this->assertSame('0400/00.00.00', (string) $this->tel);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Tel::class, $this->tel);
    }

    /** @test */
    public function can_return_a_label_and_properly_formatted_href()
    {
        $this->assertSame('0400/00.00.00', $this->tel->label);
        $this->assertSame('0400000000', $this->tel->href);
    }

    /** @test */
    public function can_format_a_value()
    {
        $this->assertTrue(is_object($this->tel->format(['label' => '1234567890', 'href' => '1234567890'])));
    }

}
