<?php 

namespace Tests\Objects\Image;

use Kabas\Objects\Image\Item;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase 
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__ . DS . '..' . DS . '..' . DS . 'TestTheme');
        if(!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
        $data = [
            'path' => 'public/TheCapricorn/foo.jpg',
            'alt' => 'Alt value'
        ];
        $this->item = new Item($data);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Item::class, $this->item);
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->expectOutputString('/foo.jpg');
        echo $this->item;
    }

    /** @test */
    public function can_return_filesize()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
        ]);
        $this->assertSame(42950, $this->item->filesize());
    }

    /** @test */
    public function can_return_core()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
        ]);
        $this->assertTrue(is_resource($this->item->getCore()));
    }

    /** @test */
    public function can_return_image_dimensions()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
        ]);
        $this->assertSame(111, $this->item->height());
        $this->assertSame(333, $this->item->width());
    }

    /** @test */
    public function can_return_iptc_data()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
        ]);
        $this->assertSame('WhiteCube', $this->item->iptc('AuthorByline'));
    }

    /** @test */
    public function can_return_exif_data()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->assertSame('WhiteCube', $this->item->exif('Artist'));
    }

    /** @test */
    public function can_return_mime_type()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->assertSame('image/jpeg', $this->item->mime());
    }

    /** @test */
    public function can_return_the_color_of_a_pixel_within_the_image()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->assertSame('#ccd0db', $this->item->pickColor(100, 100, 'hex'));
    }

    /** @test */
    public function can_return_alt()
    {
        $this->assertSame('Alt value', $this->item->alt());
    }

    /** @test */
    public function can_use_filename_as_alt()
    {
        $data = ['path' => 'public/TheCapricorn/foo.jpg'];
        $item = new Item($data);
        $this->assertSame('foo', $item->alt());
    }

    /** @test */
    public function can_generate_html_image_tag()
    {
        $this->assertSame('<img src="/foo.jpg" alt="Alt value" />', $this->item->show(false));
    }

    /** @test */
    public function can_set_errors()
    {
        $data = ['path' => 'test.png'];
        $image = new Item($data);
        $this->assertTrue($image->error);
        $data = ['alt' => 'No path'];
        $image2 = new Item($data);
        $this->assertTrue($image->error);
    }

    /** @test */
    public function can_forward_method_calls_to_editor()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->item->blur(2)->save();
        $file = PUBLIC_PATH . DS . 'TheCapricorn' . DS . 'foo-blura5f5d7a5fc80600513c623db108873af.jpg';
        $this->assertTrue(file_exists($file));
        unlink($file);
    }

}