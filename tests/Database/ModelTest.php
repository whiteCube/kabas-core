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

    /** @test */
    public function can_guess_and_return_object_name()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $structure = 'foo.json';
        });
        $this->assertContains('anonymous', $model->getObjectName());
    }

    /** @test */
    public function can_return_defined_object_name()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'foo';
        });
        $this->assertEquals('foo', $model->getObjectName());
    }

    /** @test */
    public function can_guess_and_return_repository()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'foo';
        });
        $this->assertEquals('foos', $model->getRepositoryName());
    }

    /** @test */
    public function can_return_defined_repository()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'foo';
            protected $repository = 'bar';
        });
        $this->assertEquals('bar', $model->getRepositoryName());
    }

    /** @test */
    public function can_guess_and_return_structure_file_path()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'foo';
        });
        $this->assertContains('foo.json', $model->getStructurePath());
    }

    /** @test */
    public function can_return_structure_file_path_from_defined_structure_file()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'test';
            protected $structure = 'foo.json';
        });
        $this->assertContains('foo.json', $model->getStructurePath());
    }

    /** @test */
    public function can_throw_exception_when_structure_file_not_found()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $this->expectException(FileNotFoundException::class);
        $model = (new class() extends Model {
            protected $object = 'test';
        });
        $path = $model->getStructurePath();
    }

    /** @test */
    public function can_return_all_available_paths()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'test';
            protected $structure = 'foo.json';
        });
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
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        $model = (new class() extends Model {
            protected $object = 'test';
            protected $structure = 'foo.json';
            protected $translated = false;
        });
        $paths = $model->getRepositories();
        $this->assertCount(1, $paths);
        $this->assertContains('TestTheme/content/shared/tests', $paths['shared']);
    }

    /** @test */
    public function can_load_models_fields_for_multiple_models()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        // Defining different models
        $foo = (new class() extends Model {
            protected $object = 'foo';
            protected $structure = 'foo.json';
        });
        $bar = (new class() extends Model {
            protected $object = 'bar';
            protected $structure = 'bar.json';
        });
        // Loading fields first
        $foo->getRawFields();
        $bar->getRawFields();
        // Checking if fields list is not overriden
        $this->assertEquals('number', $foo->getRawField('foo')->type);
        $this->assertEquals('textarea', $bar->getRawField('bar')->type);
    }

}
