<?php 

namespace Tests\Fields\Types;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Gallery;

class GalleryTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $data = new \stdClass;
        $data->label = 'Gallery';
        $data->type = 'gallery';
        $this->gallery = new Gallery('Gallery', null, $data);
        $this->gallery->set([['src' => 'image1.png'], ['src' => 'foo.png']]);
    }

    /** @test */
    public function a_can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Gallery::class, $this->gallery);
    }

}