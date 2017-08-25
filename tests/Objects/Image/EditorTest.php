<?php 

namespace Tests\Objects\Image;

use Tests\CreatesApplication;
use Kabas\Objects\Image\Editor;
use PHPUnit\Framework\TestCase;

class EditorTest extends TestCase 
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->editor = new Editor(UPLOADS_PATH, 'foo', 'jpg');
    }

    public function exists($file)
    {
        return file_exists(PUBLIC_UPLOADS_PATH . DS . $file);
    }

    public function del($file)
    {
        return unlink(PUBLIC_UPLOADS_PATH . DS . $file);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Editor::class, $this->editor);
    }

    /** @test */
    public function can_backup()
    {
        $this->editor->backup();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-402051f4be0cc3aad33bcf3ac3d6532b.jpg'));
        $this->del('foo-402051f4be0cc3aad33bcf3ac3d6532b.jpg');
    }

    /** @test */
    public function can_add_blur()
    {
        $this->editor->blur(4);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-5cc24a7e194ba60299892d637f6956cc.jpg'));
        $this->del('foo-5cc24a7e194ba60299892d637f6956cc.jpg');
    }

    /** @test */
    public function can_alter_brightness()
    {
        $this->editor->brightness(2);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-0b3faf0a49f23f55069452f9531fba21.jpg'));
        $this->del('foo-0b3faf0a49f23f55069452f9531fba21.jpg');
    }

    /** @test */
    public function can_draw_a_circle()
    {
        $this->editor->circle(100, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-af996d28c58dad8ae9257249b9447483.jpg'));
        $this->del('foo-af996d28c58dad8ae9257249b9447483.jpg');
    }

    /** @test */
    public function can_colorize()
    {
        $this->editor->colorize(40, 35, 22);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-226bf67a49f46de2827321aa2f4c18a5.jpg'));
        $this->del('foo-226bf67a49f46de2827321aa2f4c18a5.jpg');
    }

    /** @test */
    public function can_alter_contrast()
    {
        $this->editor->contrast(50);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-488f0970cfcbfb0d296c71f4da595813.jpg'));
        $this->del('foo-488f0970cfcbfb0d296c71f4da595813.jpg');
    }

    /** @test */
    public function can_crop()
    {
        $this->editor->crop(35, 35, 10, 15);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-11804a0360f35d37448e351b00c2dbb7.jpg'));
        $this->del('foo-11804a0360f35d37448e351b00c2dbb7.jpg');
    }

    /** @test */
    public function can_draw_an_ellipse()
    {
        $this->editor->ellipse(25, 35, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-468dd790db6dbb045082cf788ec131df.jpg'));
        $this->del('foo-468dd790db6dbb045082cf788ec131df.jpg');
    }

    /** @test */
    public function can_set_encoding_format_and_quality()
    {
        $this->editor->encode('png', 40);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-b35a457cfceb11333132f7e3e8b3cb5d.jpg'));
        $this->del('foo-b35a457cfceb11333132f7e3e8b3cb5d.jpg');
    }

    /** @test */
    public function can_fill_with_color()
    {
        $this->editor->fill('#ff0000');
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-d91cfeacd20eeeaf53de83122ff25399.jpg'));
        $this->del('foo-d91cfeacd20eeeaf53de83122ff25399.jpg');
    }

    /** @test */
    public function can_apply_filter()
    {
        $this->editor->filter(new FooFilter);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-cdc37a7dd4550f9c2a8d011841572dbf.jpg'));
        $this->del('foo-cdc37a7dd4550f9c2a8d011841572dbf.jpg');
    }

    /** @test */
    public function can_flip()
    {
        $this->editor->flip('v');
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-6b0ed16c9c6255446210cf6f76b95bef.jpg'));
        $this->del('foo-6b0ed16c9c6255446210cf6f76b95bef.jpg');
    }

    /** @test */
    public function can_crop_and_fit()
    {
        $this->editor->fit(50, 50);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-712609d7a0c583a818bbf0de3d35e93a.jpg'));
        $this->del('foo-712609d7a0c583a818bbf0de3d35e93a.jpg');
    }

    /** @test */
    public function can_adjust_gamma()
    {
        $this->editor->gamma(1.5);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-23416566222a89937bdf99f86214e2df.jpg'));
        $this->del('foo-23416566222a89937bdf99f86214e2df.jpg');
    }

    /** @test */
    public function can_greyscale()
    {
        $this->editor->greyscale();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-ae3bfc8ea1d176dabac2aa73a18e193b.jpg'));
        $this->del('foo-ae3bfc8ea1d176dabac2aa73a18e193b.jpg');
    }

    /** @test */
    public function can_heighten()
    {
        $this->editor->heighten(200);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-bde7af29dc2cb940fdf5efd45af26912.jpg'));
        $this->del('foo-bde7af29dc2cb940fdf5efd45af26912.jpg');
    }

    /** @test */
    public function can_insert_image_into_another()
    {
        $this->editor->insert(PUBLIC_PATH . DS . 'TheCapricorn' . DS . 'foo.jpg', 'top-left', 75, 25);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-d9a0da4d92298d0e35120f42a37d6530.jpg'));
        $this->del('foo-d9a0da4d92298d0e35120f42a37d6530.jpg');
    }

    /** @test */
    public function can_interlace()
    {
        $this->editor->interlace();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-5342eb2c87a33ac6b473319602814c7c.jpg'));
        $this->del('foo-5342eb2c87a33ac6b473319602814c7c.jpg');
    }

    /** @test */
    public function can_invert()
    {
        $this->editor->invert();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-20e57f32ba12b9cd13ebe9ef5e32d949.jpg'));
        $this->del('foo-20e57f32ba12b9cd13ebe9ef5e32d949.jpg');
    }

    /** @test */
    public function can_limit_colors()
    {
        $this->editor->limitColors(2);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-131d1300ca943675071609f2d2d85817.jpg'));
        $this->del('foo-131d1300ca943675071609f2d2d85817.jpg');
    }

    /** @test */
    public function can_draw_a_line()
    {
        $this->editor->line(10, 10, 100, 10, function ($draw) {
            $draw->color('#0000ff');
        });
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-a1a3db9849565595aa751253fec010fd.jpg'));
        $this->del('foo-a1a3db9849565595aa751253fec010fd.jpg');
    }

    /** Commented for now because it takes over 2 minutes to complete */
    public function can_mask()
    {
        $this->editor->encode('png');
        $this->editor->mask('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-mask'));
        $this->del('foo-mask');
    }

    /** Commented for now because it takes over 2 minutes to complete */
    public function can_add_transparency()
    {
        $this->editor->opacity(0.5);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-opacity0dot5'));
        $this->del('foo-opacity0dot5');
    }

    /** @test */
    public function can_alter_orientation()
    {
        $this->editor->orientate();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-1c2c17d1f247cacc7092449b18ba76cc.jpg'));
        $this->del('foo-1c2c17d1f247cacc7092449b18ba76cc.jpg');
    }

    /** @test */
    public function can_draw_a_pixel()
    {
        $this->editor->pixel('#ff0000', 30, 30);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-b391c0e9d31f5c7867cf206bb94a56bf.jpg'));
        $this->del('foo-b391c0e9d31f5c7867cf206bb94a56bf.jpg');
    }

    /** @test */
    public function can_pixelate()
    {
        $this->editor->pixelate(10);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-3dc254ccd0dbbc295b7e0ed38dc10891.jpg'));
        $this->del('foo-3dc254ccd0dbbc295b7e0ed38dc10891.jpg');
    }

    /** @test */
    public function can_draw_a_polygon()
    {
        $points = [40, 50, 20, 240, 60, 60];
        $this->editor->polygon($points, function($draw) {
            $draw->background('#ff0000');
        });
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-3f0b55e99285ee9854b81ec9621966ba.jpg'));
        $this->del('foo-3f0b55e99285ee9854b81ec9621966ba.jpg');
    }

    /** @test */
    public function can_draw_a_rectangle()
    {
        $this->editor->rectangle(10, 10, 40, 40, function($draw) {
            $draw->background('#ff0000');
        });
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-bb3418c1195ebbae97feab20fede1b3a.jpg'));
        $this->del('foo-bb3418c1195ebbae97feab20fede1b3a.jpg');
    }

    /** @test */
    public function can_reset_to_backup_state()
    {
        $this->editor->pixelate(10);
        $this->editor->backup('pixelated');
        $this->editor->invert();
        $this->editor->reset('pixelated');
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-299b4138ea6884f172774ae995f36077.jpg'));
        $this->del('foo-299b4138ea6884f172774ae995f36077.jpg');
    }

    /** @test */
    public function can_resize()
    {
        $this->editor->resize(50, 50);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-f1d0301c508e21700c5f6291bf538e81.jpg'));
        $this->del('foo-f1d0301c508e21700c5f6291bf538e81.jpg');
    }

    /** @test */
    public function can_resize_canvas()
    {
        $this->editor->resizeCanvas(400, 400);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-ee96fcb5a1f85bec27dc55af1d8c426a.jpg'));
        $this->del('foo-ee96fcb5a1f85bec27dc55af1d8c426a.jpg');
    }

    /** @test */
    public function can_rotate()
    {
        $this->editor->rotate(35);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-439660131ca092a558c47d38c5c1442f.jpg'));
        $this->del('foo-439660131ca092a558c47d38c5c1442f.jpg');
    }

    /** @test */
    public function can_sharpen()
    {
        $this->editor->sharpen(40);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-07712ea4e2c743faa5fd2d0b548759d0.jpg'));
        $this->del('foo-07712ea4e2c743faa5fd2d0b548759d0.jpg');
    }

    /** @test */
    public function can_add_text()
    {
        $this->editor->text('Kabas', 25, 25);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-df5eae27ec781e4f0b3454618d6d8c07.jpg'));
        $this->del('foo-df5eae27ec781e4f0b3454618d6d8c07.jpg');
    }

    /** @test */
    public function can_trim()
    {
        $this->editor->trim();
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-9e2cd3a49272eb4863464c187878b93d.jpg'));
        $this->del('foo-9e2cd3a49272eb4863464c187878b93d.jpg');
    }

    /** @test */
    public function can_widen()
    {
        $this->editor->widen(500);
        $this->editor->save(PUBLIC_UPLOADS_PATH);
        $this->assertTrue($this->exists('foo-f792e85c250898a42dbc590507b9662d.jpg'));
        $this->del('foo-f792e85c250898a42dbc590507b9662d.jpg');
    }

    /** @test */
    public function can_detect_if_has_changes()
    {
        $this->assertFalse($this->editor->hasChanges());
        $this->editor->rotate(35);
        $this->assertTrue($this->editor->hasChanges());
    }
}