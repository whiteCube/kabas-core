<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\File;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class FileTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'router' => \Kabas\Http\Router::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class
        ]);
        $data = new \stdClass;
        $data->label = "File";
        $data->type = "file";
        $this->file = new File('File', null, $data);
        $this->file->set('content/uploads/foo.jpg');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(File::class, $this->file);
    }

    /** @test */
    public function can_return_information_about_the_file()
    {
        $this->assertContains('TestTheme/content/uploads', $this->file->dirname);
        $this->assertContains('TestTheme/public/uploads', $this->file->public);
        $this->assertSame('foo.jpg', $this->file->basename);
        $this->assertSame('foo', $this->file->filename);
        $this->assertSame('jpg', $this->file->extension);
        $this->assertSame('jpg', $this->file->extension());
        $this->assertSame(42950, $this->file->size);
    }

    /** @test */
    public function returns_false_when_getting_attribute_that_does_not_exist()
    {
        $this->assertFalse($this->file->foo);
    }

}