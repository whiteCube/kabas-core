<?php 

namespace Tests\Database;

use Kabas\Database\Model;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\FileNotFoundException;

class ModelTest extends TestCase
{

    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('THEME_PATH')) define('THEME_PATH', __DIR__);
    }

    public function getModel($objectName = null, $repositoryName = null, $structureFileName = null)
    {
        return (new class([], $objectName, $repositoryName, $structureFileName) extends Model {
            static protected $object;
            public function __construct(array $attributes = [], $object, $repository, $structure) {
                static::$object = $object;
                static::$repository = $repository;
                static::$structure = $structure;
                $this->bootIfNotBooted();
                $this->syncOriginal();
            }
        });
    }

    /** @test */
    public function can_guess_and_return_object_name()
    {
        $model = $this->getModel();
        $this->assertContains('anonymous', $model->getObjectName());
    }

    /** @test */
    public function can_return_defined_object_name()
    {
        $model = $this->getModel('foo');
        $this->assertEquals('foo', $model->getObjectName());
    }

    /** @test */
    public function can_guess_and_return_repository()
    {
        $model = $this->getModel('foo');
        $this->assertEquals('foos', $model->getRepository());
    }

    /** @test */
    public function can_return_defined_repository()
    {
        $model = $this->getModel('foo', 'bar');
        $this->assertEquals('bar', $model->getRepository());
    }

    /** @test */
    public function can_guess_and_return_structure_file_path()
    {
        $model = $this->getModel('foo');
        $this->assertContains('foo.json', $model->getStructurePath());
    }

    /** @test */
    public function can_return_structure_file_path_from_defined_structure_file()
    {
        $model = $this->getModel('test', null, 'foo.json');
        $this->assertContains('foo.json', $model->getStructurePath());
    }

    /** @test */
    public function can_throw_exception_when_structure_file_not_found()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(FileNotFoundException::class);
        $model = $this->getModel('test');
        $path = $model->getStructurePath();
    }

}
