<?php

namespace Tests;

use Kabas\App;
use Kabas\Utils\File;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\JsonException;
use Kabas\Exceptions\FileNotFoundException;

class FileTest extends TestCase
{
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
        File::deleteJson(__DIR__ . '/test/dummy1');
        File::deleteJson(__DIR__ . '/test/dummy2');
        File::deleteJson(__DIR__ . '/test/foo/bar');
        rmdir(__DIR__ . '/test/foo');
        rmdir(__DIR__ . '/test');
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
        $this->expectException(FileNotFoundException::class);
        File::loadJson(__DIR__ . '/test.json');
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
        File::deleteJson(__DIR__ . '/dummy');
        $this->assertFileNotExists(__DIR__ . '/dummy.json');
    }

    /** @test */
    public function can_read_the_contents_of_a_file()
    {
        File::write('test', __DIR__ . '/dummy.json');
        $this->assertEquals('test', File::read(__DIR__ . '/dummy.json'));
        File::deleteJson(__DIR__ . '/dummy');
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
        File::deleteJson(__DIR__ . '/test');
    }

    /** @test */
    public function throws_exception_if_loading_invalid_json_from_directory()
    {
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
        $this->createFakeFiles();
        $data = File::loadJsonFromDir(__DIR__ . '/test');
        $this->assertEquals('bar', $data[0]->foo);
        $this->assertEquals('bar', $data['foo'][0]->foo);
        $this->deleteFakeFiles();
    }

    public function tearDown()
    {
        if(is_dir(__DIR__ . '/test')) $this->deleteFakeFiles();
    }

}