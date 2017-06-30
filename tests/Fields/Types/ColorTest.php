<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Color;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class ColorTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = "Title";
        $data->type = "text";
        $this->color = new Color('Color', $data);
        $this->color->set('#ffffff');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Color::class, $this->color);
    }

    /** @test */
    public function can_be_set_with_hex_or_rgb()
    {
        $this->color->set('#fefefe');
        $this->assertSame('#fefefe', $this->color->get());

        $this->color->set('#ccc');
        $this->assertSame('#cccccc', $this->color->get());

        $this->color->set('fefefe');
        $this->assertSame('#fefefe', $this->color->get());

        $this->color->set('ccc');
        $this->assertSame('#cccccc', $this->color->get());

        $this->color->rgb()->set('rgb(255,255,255)');
        $this->assertSame('rgb(255,255,255)', $this->color->get());
    }

    /** @test */
    public function throws_exception_when_setting_incorrect_value()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(TypeException::class);
        $this->color->set(['foo']);
    }

    /** @test */
    public function can_set_output_mode_to_hex_or_rgb()
    {
        $this->assertSame('rgb(255,255,255)', $this->color->rgb()->get());
        $this->assertSame('#ffffff', $this->color->hex()->get());
    }

    /** @test */
    public function can_return_the_individual_red_green_and_blue_values()
    {
        $this->color->set('#4e5fce');
        $this->assertSame(78, $this->color->red());
        $this->assertSame(95, $this->color->green());
        $this->assertSame(206, $this->color->blue());
    }

}