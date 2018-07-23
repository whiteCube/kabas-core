<?php

namespace Tests\Utils;

use Kabas\App;
use Kabas\Utils\File;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\JsonException;
use Kabas\Exceptions\FileNotFoundException;

class FileTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;
    
    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
    }

    public function createFakeFiles()
    {
        $data = ['foo' => 'bar'];
        mkdir(__DIR__ . '/test');
        mkdir(__DIR__ . '/test/foo');
        File::writeJson($data, __DIR__ . '/test/dummy1');
        File::writeJson($data, __DIR__ . '/test/dummy2');
        File::writeJson($data, __DIR__ . '/test/foo/bar');
    }

    public function createFakeInvalidFiles()
    {
        mkdir(__DIR__ . '/test');
        mkdir(__DIR__ . '/test/foo');
        File::write('test', __DIR__ . '/test/dummy1.json');
        File::write('test', __DIR__ . '/test/dummy2.json');
        File::write('test', __DIR__ . '/test/foo/bar.json');
    }

    public function deleteFakeFiles()
    {
        $this->recursive_rmdir(__DIR__ . '/test');
    }

    public function recursive_rmdir($directory)
    {
        foreach(scandir($directory) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir($directory . DS . $file)) $this->recursive_rmdir($directory . DS . $file);
            else unlink($directory . DS . $file);
        }
        rmdir($directory);
    }

    /** @test */
    public function can_get_the_contents_of_a_json_file()
    {
        $this->createFakeFiles();
        $data = File::loadJson(__DIR__ . '/test/dummy1.json');
        $this->assertEquals('bar', $data->foo);
        $this->deleteFakeFiles();
    }

    /** @test */
    public function throws_exception_if_loading_an_invalid_json_file()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->createFakeInvalidFiles();
        $exception = null;
        try {
            File::loadJsonFromDir(__DIR__ . '/test');
        } catch(JsonException $e) {
            $exception = $e;   
        }
        $this->assertInstanceOf(JsonException::class, $exception);
        $this->deleteFakeFiles();
    }

    /** @test */
    public function throws_exception_if_loading_file_that_does_not_exist()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(FileNotFoundException::class);
        File::loadJson(__DIR__ . '/test.json');
    }

    /** @test */
    public function can_load_files_that_do_and_do_not_exist_without_exceptions()
    {
        $this->createFakeFiles();
        $this->assertNull(File::loadJsonIfValid(__DIR__ . '/test.json'));
        $data = File::loadJsonIfValid(__DIR__ . '/test/dummy1.json');
        $this->assertEquals('bar', $data->foo);
        $this->deleteFakeFiles();
    }

    /** @test */
    public function can_determine_if_a_file_has_json_extension()
    {
        $this->createFakeFiles();
        $this->assertTrue(File::isJson(__DIR__ . '/test/dummy1.json'));
        $this->assertFalse(File::isJson(__DIR__ . '/FileTest.php'));
        $this->deleteFakeFiles();
    }

    /** @test */
    public function can_erase_a_json_file_from_disk()
    {
        File::write('test', __DIR__ . '/dummy.json');
        File::delete(__DIR__ . '/dummy.json');
        $this->assertFileNotExists(__DIR__ . '/dummy.json');
    }

    /** @test */
    public function can_read_the_contents_of_a_file()
    {
        File::write('test', __DIR__ . '/dummy.json');
        $this->assertEquals('test', File::read(__DIR__ . '/dummy.json'));
        File::delete(__DIR__ . '/dummy.json');
    }

    /** @test */
    public function can_parse_a_directory_and_return_its_structure()
    {
        $this->createFakeFiles();
        $structure = File::parseDirectory(__DIR__ . '/test');
        $this->assertCount(3, $structure);
        $this->assertEquals('dummy2.json', $structure[1]);
        $this->assertEquals('bar.json', $structure['foo'][0]);
        $this->deleteFakeFiles();
    }


    /** @test */
    public function can_write_a_proper_json_file()
    {
        $data = ['foo' => 'bar'];
        File::writeJson($data, __DIR__ . '/test');
        $this->assertEquals('bar', File::loadJson(__DIR__ . '/test.json')->foo);
        File::delete(__DIR__ . '/test.json');
    }

    /** @test */
    public function throws_exception_if_loading_invalid_json_from_directory()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->createFakeInvalidFiles();
        $exception = null;
        try {
            $data = File::loadJsonFromDir(__DIR__ . '/test');
        } catch(JsonException $e) {
            $exception = $e;   
        }
        $this->assertInstanceOf(JsonException::class, $exception);
        $this->deleteFakeFiles();
    }

    /** @test */
    public function can_read_json_data_from_a_directory()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->createFakeFiles();
        $data = File::loadJsonFromDir(__DIR__ . DS . 'test');
        $this->assertEquals('bar', $data['dummy1']->foo);
        $this->assertEquals('bar', $data['dummy2']->foo);
        $this->deleteFakeFiles();
    }

    /** @test */
    public function can_make_new_directory()
    {
        File::mkdir(__DIR__.'/foo');
        $this->assertTrue(is_dir(__DIR__.'/foo'));
        File::writeJson(['foo' => 'bar'], __DIR__ . '/foo/bar');
        $this->assertTrue(File::mkdir(__DIR__.'/foo'));
        $this->assertTrue(file_exists(__DIR__.'/foo/bar.json'));
        $this->recursive_rmdir(__DIR__.'/foo');
    }

    /** @test */
    public function can_copy_file()
    {
        File::writeJson(['foo' => 'bar'], __DIR__ . '/test/original');
        File::writeJson(['foo' => 'bar', 'bar' => 'foo'], __DIR__ . '/test/overwrite');
        $original = File::copy(__DIR__.'/test/original.json', __DIR__.'/test/foo/bar.json');
        $overwrite = File::copy(__DIR__.'/test/overwrite.json', __DIR__.'/test/foo/bar.json', false);
        $this->assertSame(filesize(__DIR__ . '/test/original.json'), filesize(__DIR__.'/test/foo/bar.json'));
    }

    /** @test */
    public function can_copy_folders_recursively()
    {
        File::copyDir(__DIR__ . DS . '..' . DS . 'TestTheme' . DS . 'storage', __DIR__ . DS . 'test');
        $this->assertTrue(is_dir(__DIR__ . DS . 'test'));
    }

    public function tearDown()
    {
        if(is_dir(__DIR__ . '/test')) $this->deleteFakeFiles();
    }

}