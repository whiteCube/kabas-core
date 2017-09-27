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

    public function getModel($objectName = null, $repositoryName = null, $structureFileName = null, $translated = true)
    {
        return (new class([], $objectName, $repositoryName, $structureFileName, $translated) extends Model {
            protected $object;
            protected $repository;
            protected $structure;
            public function __construct(array $attributes = [], $object = null, $repository = null, $structure = null, $translated = true) {
                $this->object = $object;
                $this->repository = $repository;
                $this->structure = $structure;
                $this->translated = $translated;
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
        $this->assertEquals('foos', $model->getRepositoryName());
    }

    /** @test */
    public function can_return_defined_repository()
    {
        $model = $this->getModel('foo', 'bar');
        $this->assertEquals('bar', $model->getRepositoryName());
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

    /** @test */
    public function can_return_all_available_paths()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $model = $this->getModel('test', null, 'foo.json');
        $paths = $model->getRepositories();
        $this->assertCount(3, $paths);
        $this->assertContains('TestTheme/content/shared/tests', $paths['shared']);
        $this->assertContains('TestTheme/content/en-GB/tests', $paths['en-GB']);
        $this->assertContains('TestTheme/content/fr-FR/tests', $paths['fr-FR']);
    }

    /** @test */
    public function can_return_shared_path_for_untranslatable_model()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $model = $this->getModel('test', null, 'foo.json', false);
        $paths = $model->getRepositories();
        $this->assertCount(1, $paths);
        $this->assertContains('TestTheme/content/shared/tests', $paths['shared']);
    }

}
