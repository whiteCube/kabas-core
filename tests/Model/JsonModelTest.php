<?php

namespace Tests\Model;

use Kabas\Model\JsonModel;
use PHPUnit\Framework\TestCase;

define('DS', DIRECTORY_SEPARATOR);
define('THEME_PATH', __DIR__);

class JsonModelTest extends TestCase
{
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
}
