<?php 

namespace Tests\Fields;

use Kabas\Fields\Types\File;
use Tests\CreatesApplication;
use Kabas\Objects\Uploads\Item;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class UploadableTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $_FILES['File'] = [
            'size' => 424242,
            'name' => 'File.png'
        ];
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'router' => \Kabas\Http\Routes\Router::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class
        ]);
        $data = new \stdClass;
        $data->label = "File";
        $data->type = "file";
        $this->file = new File('File', null, $data);
        $this->file->set(__DIR__ . '/../../TestTheme/public/index.php');
    }

    /** @test */
    public function has_a_reference_to_the_corresponding_upload()
    {
        $this->assertInstanceOf(Item::class, $this->file->upload);
    }


}