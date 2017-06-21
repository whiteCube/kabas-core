<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS ));
        $data = new \stdClass;
        $data->label = 'Image';
        $data->type = 'image';
        $this->image = new Image('Image', null, $data);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }

    /** @test */
    public function can_format_a_value_to_make_it_usable()
    {
        $this->assertSame('img.png', $this->image->format(['path' => 'img.png', 'alt' => 'foo'])->path);
    }

    /** @test */
    public function can_forward_method_calls_to_image_object()
    {
        $this->assertFalse($this->image->alt());        
        $this->image->set((object) ['path' => 'foo.png', 'alt' => 'foo']);
        $this->assertSame('foo', $this->image->alt());
    }

}
