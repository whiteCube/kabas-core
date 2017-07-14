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
        $dir = PUBLIC_PATH . DS . 'TheCapricorn';
        $file = 'foo';
        $ext = 'jpg';
        $this->editor = new Editor($dir, $file, $ext);
    }

    public function exists($file, $ext = '.jpg')
    {
        return file_exists(PUBLIC_PATH . DS . 'TheCapricorn' . DS . $file . $ext);
    }

    public function del($file, $ext = '.jpg')
    {
        return unlink(PUBLIC_PATH . DS . 'TheCapricorn' . DS . $file . $ext);
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
        $this->editor->save();
        $this->assertTrue($this->exists('foo-backup'));
        $this->del('foo-backup');
    }

    /** @test */
    public function can_add_blur()
    {
        $this->editor->blur(4);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-blur4'));
        $this->del('foo-blur4');
    }

    /** @test */
    public function can_alter_brightness()
    {
        $this->editor->brightness(2);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-brightness2'));
        $this->del('foo-brightness2');
    }

    /** @test */
    public function can_draw_a_circle()
    {
        $this->editor->circle(100, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-circleX50Y50'));
        $this->del('foo-circleX50Y50');
    }

    /** @test */
    public function can_colorize()
    {
        $this->editor->colorize(40, 35, 22);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-r40g35b22'));
        $this->del('foo-r40g35b22');
    }

    /** @test */
    public function can_alter_contrast()
    {
        $this->editor->contrast(50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-contrast50'));
        $this->del('foo-contrast50');
    }

    /** @test */
    public function can_crop()
    {
        $this->editor->crop(35, 35, 10, 15);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-crop35x35'));
        $this->del('foo-crop35x35');
    }

    /** @test */
    public function can_draw_an_ellipse()
    {
        $this->editor->ellipse(25, 35, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-ellipseX50Y50'));
        $this->del('foo-ellipseX50Y50');
    }

    /** @test */
    public function can_set_encoding_format_and_quality()
    {
        $this->editor->encode('png', 40);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-encode'));
        $this->del('foo-encode');
    }

    /** @test */
    public function can_fill_with_color()
    {
        $this->editor->fill('#ff0000');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-fill'));
        $this->del('foo-fill');
    }

    /** @test */
    public function can_apply_filter()
    {
        $this->editor->filter(new FooFilter);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-filter-Tests\Objects\Image\FooFilter'));
        $this->del('foo-filter-Tests\Objects\Image\FooFilter');
    }

    /** @test */
    public function can_flip()
    {
        $this->editor->flip('v');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-flip-v'));
        $this->del('foo-flip-v');
    }

    /** @test */
    public function can_crop_and_fit()
    {
        $this->editor->fit(50, 50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-50x50'));
        $this->del('foo-50x50');
    }

    /** @test */
    public function can_adjust_gamma()
    {
        $this->editor->gamma(1.5);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-gamma1dot5'));
        $this->del('foo-gamma1dot5');
    }

    /** @test */
    public function can_greyscale()
    {
        $this->editor->greyscale();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-greyscale'));
        $this->del('foo-greyscale');
    }

    /** @test */
    public function can_heighten()
    {
        $this->editor->heighten(200);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-heighten200'));
        $this->del('foo-heighten200');
    }

    /** @test */
    public function can_insert_image_into_another()
    {
        $dir = PUBLIC_PATH . DS . 'TheCapricorn';
        $file = 'foo';
        $ext = 'jpg';
        $editor = new Editor($dir, $file, $ext);
        $editor->fill('#ff0000');
        $editor->save();
        $this->editor->insert(PUBLIC_PATH . DS . 'TheCapricorn' . DS . 'foo-fill.jpg');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-insert'));
        $this->del('foo-fill');
        $this->del('foo-insert');
    }

    /** @test */
    public function can_interlace()
    {
        $this->editor->interlace();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-interlace'));
        $this->del('foo-interlace');
    }

    /** @test */
    public function can_invert()
    {
        $this->editor->invert();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-invert'));
        $this->del('foo-invert');
    }

    /** @test */
    public function can_limit_colors()
    {
        $this->editor->limitColors(2);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-2colors'));
        $this->del('foo-2colors');
    }

    /** @test */
    public function can_draw_a_line()
    {
        $this->editor->line(10, 10, 100, 10, function ($draw) {
            $draw->color('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-line'));
        $this->del('foo-line');
    }

    /** Commented for now because it takes over 2 minutes to complete */
    public function can_mask()
    {
        $this->editor->encode('png');
        $this->editor->mask('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-mask'));
        $this->del('foo-mask');
    }

    /** Commented for now because it takes over 2 minutes to complete */
    public function can_add_transparency()
    {
        $this->editor->opacity(0.5);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-opacity0dot5'));
        $this->del('foo-opacity0dot5');
    }

    /** @test */
    public function can_alter_orientation()
    {
        $this->editor->orientate();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-orientate'));
        $this->del('foo-orientate');
    }

    /** @test */
    public function can_draw_a_pixel()
    {
        $this->editor->pixel('#ff0000', 30, 30);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixel'));
        $this->del('foo-pixel');
    }

    /** @test */
    public function can_pixelate()
    {
        $this->editor->pixelate(10);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixelate'));
        $this->del('foo-pixelate');
    }

    /** @test */
    public function can_draw_a_polygon()
    {
        $points = array(
            40,  50, 
            20,  240,
            60,  60
        );
        $this->editor->polygon($points, function($draw) {
            $draw->background('#ff0000');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-polygon'));
        $this->del('foo-polygon');
    }

    /** @test */
    public function can_draw_a_rectangle()
    {
        $this->editor->rectangle(10, 10, 40, 40, function($draw) {
            $draw->background('#ff0000');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-rectangle'));
        $this->del('foo-rectangle');
    }

    /** @test */
    public function can_reset_to_backup_state()
    {
        $this->editor->pixelate(10);
        $this->editor->backup('pixelated');
        $this->editor->invert();
        $this->editor->reset('pixelated');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixelate-backup-invert-reset'));
        $this->del('foo-pixelate-backup-invert-reset');
    }

    /** @test */
    public function can_resize()
    {
        $this->editor->resize(50, 50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-50x50'));
        $this->del('foo-50x50');
    }

    /** @test */
    public function can_resize_canvas()
    {
        $this->editor->resizeCanvas(400, 400);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-400x400'));
        $this->del('foo-400x400');
    }

    /** @test */
    public function can_rotate()
    {
        $this->editor->rotate(35);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-rotate35'));
        $this->del('foo-rotate35');
    }

    /** @test */
    public function can_detect_if_has_changes()
    {
        $this->assertFalse($this->editor->hasChanges());
        $this->editor->rotate(35);
        $this->assertTrue($this->editor->hasChanges());
    }

    /** @test */
    public function can_sharpen()
    {
        $this->editor->sharpen(40);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-sharpen40'));
        $this->del('foo-sharpen40');
    }

    /** @test */
    public function can_add_text()
    {
        $this->editor->text('Kabas', 25, 25);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-text'));
        $this->del('foo-text');
    }

    /** @test */
    public function can_trim()
    {
        $this->editor->trim();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-trim'));
        $this->del('foo-trim');
    }

    /** @test */
    public function can_widen()
    {
        $this->editor->widen(500);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-widen500'));
        $this->del('foo-widen500');
    }

    

}