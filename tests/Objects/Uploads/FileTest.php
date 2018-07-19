<?php
namespace Tests\Objects\Uploads;

use Kabas\Objects\Uploads\File;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class FileTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->file = new File('content/uploads/foo.jpg');
    }

    /** @test */
    public function can_be_instanciated()
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    /** @test */
    public function can_return_the_file_url_when_cast_as_string()
    {
        $this->assertTrue(is_string((string) $this->file));
        $this->assertSame('http://www.foo.com/uploads/foo.jpg', (string) $this->file);
    }

    /** @test */
    public function can_return_the_file_url()
    {
        $this->assertTrue(is_string($this->file->src()));
        $this->assertSame('http://www.foo.com/uploads/foo.jpg', $this->file->src());
    }

    /** @test */
    public function can_copy_the_file_to_the_public_folder()
    {
        unlink(PUBLIC_PATH . DS . 'uploads' . DS . 'foo.jpg');
        $this->assertFileNotExists(PUBLIC_PATH . DS . 'uploads' . DS . 'foo.jpg');
        $this->file->apply();
        $this->assertFileExists(PUBLIC_PATH . DS . 'uploads' . DS . 'foo.jpg');
        unlink(PUBLIC_PATH . DS . 'uploads' . DS . 'foo.jpg');
    }
}
