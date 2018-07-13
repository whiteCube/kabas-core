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
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'router' => \Kabas\Http\Routes\Router::class
        ]);
        $this->item = new Item(['path' => 'content/uploads/foo.jpg', 'alt' => 'Foobar']);
    }

    public function del($file)
    {
        return unlink(PUBLIC_UPLOADS_PATH . DS . $file);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Item::class, $this->item);
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->result = $this->catch(function(){
            echo $this->item;
        });
        $this->see('uploads/foo.jpg');
        $this->del('foo.jpg');
    }

    /** @test */
    public function can_return_filesize()
    {
        $this->assertSame(42950, $this->item->filesize());
    }

    /** @test */
    public function can_return_core()
    {
        $this->assertTrue(is_resource($this->item->getCore()));
    }

    /** @test */
    public function can_return_image_dimensions()
    {
        $this->assertSame(111, $this->item->height());
        $this->assertSame(333, $this->item->width());
    }

    /** @test */
    public function can_return_iptc_data()
    {
        $this->assertSame('WhiteCube', $this->item->iptc('AuthorByline'));
    }

    /** @test */
    public function can_return_exif_data()
    {
        $this->assertSame('WhiteCube', $this->item->exif('Artist'));
    }

    /** @test */
    public function can_return_mime_type()
    {
        $this->assertSame('image/jpeg', $this->item->mime());
    }

    /** @test */
    public function can_return_the_color_of_a_pixel_within_the_image()
    {
        $this->assertSame('#ccd0db', $this->item->pickColor(100, 100, 'hex'));
    }

    /** @test */
    public function can_return_alt()
    {
        $this->assertSame('Foobar', $this->item->alt());
    }

    /** @test */
    public function can_use_filename_as_alt()
    {
        $item = new Item(['path' => 'public/TheCapricorn/foo.jpg']);
        $this->assertSame('foo', $item->alt());
    }

    /** @test */
    public function can_generate_html_image_tag()
    {
        $this->result = $this->catch(function(){
            $this->item->show();
        });
        $this->see('<img src="http://www.foo.com/uploads/foo.jpg" alt="Foobar" />');
        $this->assertSame('<img src="http://www.foo.com/uploads/foo.jpg" alt="Foobar" />', $this->item->show(false));
        $this->del('foo.jpg');
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
        $this->item->blur(2)->src();
        $this->assertTrue(file_exists(PUBLIC_UPLOADS_PATH . DS . 'foo-aaf678384788b1296dc98b4af034b866.jpg'));
        $this->del('foo-aaf678384788b1296dc98b4af034b866.jpg');
    }

}