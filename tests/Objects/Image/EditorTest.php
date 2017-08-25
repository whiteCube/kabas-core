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
        $this->editor->save();
        $this->assertTrue($this->exists('foo-bluradcb9b75260590ff6058773ddcb9ddd6'));
        $this->del('foo-bluradcb9b75260590ff6058773ddcb9ddd6');
    }

    /** @test */
    public function can_alter_brightness()
    {
        $this->editor->brightness(2);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-brightnessa5f5d7a5fc80600513c623db108873af'));
        $this->del('foo-brightnessa5f5d7a5fc80600513c623db108873af');
    }

    /** @test */
    public function can_draw_a_circle()
    {
        $this->editor->circle(100, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-circlebd69f297cd2a39be6bdc9377cf12eddf'));
        $this->del('foo-circlebd69f297cd2a39be6bdc9377cf12eddf');
    }

    /** @test */
    public function can_colorize()
    {
        $this->editor->colorize(40, 35, 22);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-colorize3140a865cb8a6cc6f2d89d663b657f2c'));
        $this->del('foo-colorize3140a865cb8a6cc6f2d89d663b657f2c');
    }

    /** @test */
    public function can_alter_contrast()
    {
        $this->editor->contrast(50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-contrast68d22f41eb0a025961de0a8e20e64064'));
        $this->del('foo-contrast68d22f41eb0a025961de0a8e20e64064');
    }

    /** @test */
    public function can_crop()
    {
        $this->editor->crop(35, 35, 10, 15);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-crop4363047344496dceabdada1ea0d91e23'));
        $this->del('foo-crop4363047344496dceabdada1ea0d91e23');
    }

    /** @test */
    public function can_draw_an_ellipse()
    {
        $this->editor->ellipse(25, 35, 50, 50, function ($draw) {
            $draw->background('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-ellipse5c0a4ae84de1bb97f8d04daa03af5fc4'));
        $this->del('foo-ellipse5c0a4ae84de1bb97f8d04daa03af5fc4');
    }

    /** @test */
    public function can_set_encoding_format_and_quality()
    {
        $this->editor->encode('png', 40);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-encode02991df65bdb2707fa52544fc660fd97'));
        $this->del('foo-encode02991df65bdb2707fa52544fc660fd97');
    }

    /** @test */
    public function can_fill_with_color()
    {
        $this->editor->fill('#ff0000');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-fill3c7ebbba89b30b4c85bacd20463dd2e3'));
        $this->del('foo-fill3c7ebbba89b30b4c85bacd20463dd2e3');
    }

    /** @test */
    public function can_apply_filter()
    {
        $this->editor->filter(new FooFilter);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-filter105fabd09b4031d5d8566a5937016692'));
        $this->del('foo-filter105fabd09b4031d5d8566a5937016692');
    }

    /** @test */
    public function can_flip()
    {
        $this->editor->flip('v');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-flipa2ef886f60da0eed54e35cfbd189e202'));
        $this->del('foo-flipa2ef886f60da0eed54e35cfbd189e202');
    }

    /** @test */
    public function can_crop_and_fit()
    {
        $this->editor->fit(50, 50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-fitc2e895775853fbce22c5fa8238390e0d'));
        $this->del('foo-fitc2e895775853fbce22c5fa8238390e0d');
    }

    /** @test */
    public function can_adjust_gamma()
    {
        $this->editor->gamma(1.5);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-gamma823762f4bcd8c42fd66067371ddfbaeb'));
        $this->del('foo-gamma823762f4bcd8c42fd66067371ddfbaeb');
    }

    /** @test */
    public function can_greyscale()
    {
        $this->editor->greyscale();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-greyscale40cd750bba9870f18aada2478b24840a'));
        $this->del('foo-greyscale40cd750bba9870f18aada2478b24840a');
    }

    /** @test */
    public function can_heighten()
    {
        $this->editor->heighten(200);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-heighten8f5aa12cdf6c786350be18f502a1be0c'));
        $this->del('foo-heighten8f5aa12cdf6c786350be18f502a1be0c');
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
        $this->editor->insert(PUBLIC_PATH . DS . 'TheCapricorn' . DS . 'foo-fill3c7ebbba89b30b4c85bacd20463dd2e3.jpg');
        $this->editor->save();
        $filename = 'foo-insert' . md5(serialize([PUBLIC_PATH . DS . 'TheCapricorn' . DS . 'foo-fill3c7ebbba89b30b4c85bacd20463dd2e3.jpg']));
        $this->assertTrue($this->exists($filename));
        $this->del('foo-fill3c7ebbba89b30b4c85bacd20463dd2e3');
        $this->del($filename);
    }

    /** @test */
    public function can_interlace()
    {
        $this->editor->interlace();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-interlace40cd750bba9870f18aada2478b24840a'));
        $this->del('foo-interlace40cd750bba9870f18aada2478b24840a');
    }

    /** @test */
    public function can_invert()
    {
        $this->editor->invert();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-invert40cd750bba9870f18aada2478b24840a'));
        $this->del('foo-invert40cd750bba9870f18aada2478b24840a');
    }

    /** @test */
    public function can_limit_colors()
    {
        $this->editor->limitColors(2);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-limitColorsa5f5d7a5fc80600513c623db108873af'));
        $this->del('foo-limitColorsa5f5d7a5fc80600513c623db108873af');
    }

    /** @test */
    public function can_draw_a_line()
    {
        $this->editor->line(10, 10, 100, 10, function ($draw) {
            $draw->color('#0000ff');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-line4f063de95639288b48ca28109924e87f'));
        $this->del('foo-line4f063de95639288b48ca28109924e87f');
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
        $this->assertTrue($this->exists('foo-orientate40cd750bba9870f18aada2478b24840a'));
        $this->del('foo-orientate40cd750bba9870f18aada2478b24840a');
    }

    /** @test */
    public function can_draw_a_pixel()
    {
        $this->editor->pixel('#ff0000', 30, 30);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixel3d106fde6106dd53faddc6a9f66e40a9'));
        $this->del('foo-pixel3d106fde6106dd53faddc6a9f66e40a9');
    }

    /** @test */
    public function can_pixelate()
    {
        $this->editor->pixelate(10);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixelatec8bde69a9f79ca24059e3807f9a3bcf8'));
        $this->del('foo-pixelatec8bde69a9f79ca24059e3807f9a3bcf8');
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
        $this->assertTrue($this->exists('foo-polygonbc1366cebf7cd62ec1a30ee949fb2579'));
        $this->del('foo-polygonbc1366cebf7cd62ec1a30ee949fb2579');
    }

    /** @test */
    public function can_draw_a_rectangle()
    {
        $this->editor->rectangle(10, 10, 40, 40, function($draw) {
            $draw->background('#ff0000');
        });
        $this->editor->save();
        $this->assertTrue($this->exists('foo-rectanglec8537a1e0e12301631713dd57f91d5a7'));
        $this->del('foo-rectanglec8537a1e0e12301631713dd57f91d5a7');
    }

    /** @test */
    public function can_reset_to_backup_state()
    {
        $this->editor->pixelate(10);
        $this->editor->backup('pixelated');
        $this->editor->invert();
        $this->editor->reset('pixelated');
        $this->editor->save();
        $this->assertTrue($this->exists('foo-pixelatec8bde69a9f79ca24059e3807f9a3bcf8-backupcb0a26bb0638a938266393507a780848-invert40cd750bba9870f18aada2478b24840a-resetcb0a26bb0638a938266393507a780848'));
        $this->del('foo-pixelatec8bde69a9f79ca24059e3807f9a3bcf8-backupcb0a26bb0638a938266393507a780848-invert40cd750bba9870f18aada2478b24840a-resetcb0a26bb0638a938266393507a780848');
    }

    /** @test */
    public function can_resize()
    {
        $this->editor->resize(50, 50);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-resizec2e895775853fbce22c5fa8238390e0d'));
        $this->del('foo-resizec2e895775853fbce22c5fa8238390e0d');
    }

    /** @test */
    public function can_resize_canvas()
    {
        $this->editor->resizeCanvas(400, 400);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-resizeCanvas146d830bd399ae02cb0c7fd29dba555f'));
        $this->del('foo-resizeCanvas146d830bd399ae02cb0c7fd29dba555f');
    }

    /** @test */
    public function can_rotate()
    {
        $this->editor->rotate(35);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-rotate3c2f09ef3307c7968f556ae704095acf'));
        $this->del('foo-rotate3c2f09ef3307c7968f556ae704095acf');
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
        $this->assertTrue($this->exists('foo-sharpenb4267a67df0cfb11e30d6396f858b5c9'));
        $this->del('foo-sharpenb4267a67df0cfb11e30d6396f858b5c9');
    }

    /** @test */
    public function can_add_text()
    {
        $this->editor->text('Kabas', 25, 25);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-text916feeae665310f9d5eea0a60dd1dfb3'));
        $this->del('foo-text916feeae665310f9d5eea0a60dd1dfb3');
    }

    /** @test */
    public function can_trim()
    {
        $this->editor->trim();
        $this->editor->save();
        $this->assertTrue($this->exists('foo-trim40cd750bba9870f18aada2478b24840a'));
        $this->del('foo-trim40cd750bba9870f18aada2478b24840a');
    }

    /** @test */
    public function can_widen()
    {
        $this->editor->widen(500);
        $this->editor->save();
        $this->assertTrue($this->exists('foo-widen35b7f6c570420def4713f94c889d15e4'));
        $this->del('foo-widen35b7f6c570420def4713f94c889d15e4');
    }

    

}