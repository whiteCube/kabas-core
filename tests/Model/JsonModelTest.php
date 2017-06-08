<?php

namespace Tests\Model;

use Tests\CreatesApplication;
use Kabas\Model\JsonModel;
use PHPUnit\Framework\TestCase;

define('THEME_PATH', __DIR__);

class JsonModelTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
    }

    /** @test */
    public function can_be_properly_instanciated()
    {
        $model = new TestJsonModel();
        $this->assertInstanceOf(TestJsonModel::class, $model);
    }

    /** @test */
    public function can_guess_object_name_from_class_name()
    {
        $model = new TestJsonModel();
        $this->assertEquals('testJsonModel', $model->getObjectName());
    }

    /** @test */
    public function can_manually_set_object_name()
    {
        $model = new class extends JsonModel{
            protected $object = 'testJsonModel';
        };
        $this->assertEquals('testJsonModel', $model->getObjectName());
    }

    /** @test */
    public function can_guess_repository_from_object_name()
    {
        $model = new TestJsonModel();
        $this->assertEquals('testJsonModels', $model->getRepository());
    }

    /** @test */
    public function can_manually_set_repository_name()
    {
        $model = new class extends JsonModel{
            protected $object = 'testJsonModel';
            protected $repository = 'tests';
        };
        $this->assertEquals('tests', $model->getRepository());
    }

    /** @test */
    public function can_guess_structure_path_from_object_name()
    {
        $model = new TestJsonModel();
        $this->assertEquals(THEME_PATH . DS . 'structures' . DS . 'models' . DS . 'testJsonModel.json', $model->getStructurePath());
    }

    /** @test */
    public function can_manually_set_structure_file()
    {
        $model = new class extends JsonModel{
            protected $structure = 'testJsonModel.json';
        };
        $this->assertEquals(THEME_PATH . DS . 'structures' . DS . 'models' . DS . 'testJsonModel.json', $model->getStructurePath());
    }

    /** @test */
    public function cannot_be_instantiated_without_existing_structure_file()
    {
        $this->expectException(\Kabas\Exceptions\FileNotFoundException::class);
        $model = new class extends JsonModel{
            protected $structure = 'no-file.json';
        };
    }

    /** @test */
    public function can_load_fields_from_structure_file()
    {
        $model = new TestJsonModel();
        $this->assertObjectHasAttribute('test', $model->getFields());
    }

    /** @test */
    public function can_forward_static_call_to_driver_and_find_storage_path_for_default_locale()
    {
        $expected = CONTENT_PATH . DS . 'en-GB' . DS . 'testJsonModels';
        $returned = TestJsonModel::getContentPath();
        $this->assertEquals($expected, $returned);
    }
}
