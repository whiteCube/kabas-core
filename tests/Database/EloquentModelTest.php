<?php 

namespace Tests\Database;

use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Kabas\Fields\Types\Textarea;
use Kabas\Content\Container as Content;

class EloquentModelTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class
        ]);
        Content::setParsed(true);
        if(!defined('THEME_PATH')) define('THEME_PATH', __DIR__);
    }

    /** @test */
    public function can_instantiate_and_fill_eloquent_model()
    {
        $model = new EloquentModel(['foo' => 'bar']);
        $this->assertInstanceOf(EloquentModel::class, $model);
        $this->assertInstanceOf(Textarea::class, $model->foo);
        $this->assertEquals('bar', $model->foo->get());
    }

    /** @test */
    public function can_update_eloquent_model()
    {
        $model = new EloquentModel(['foo' => 'bar']);
        $model->foo = "test";
        $this->assertInstanceOf(Textarea::class, $model->foo);
        $this->assertEquals('test', $model->foo->get());
    }

    /** @test */
    public function can_create_model_from_builder_query()
    {
        $reference = new EloquentModel();
        $queryResultAttributes = new \stdClass();
        $queryResultAttributes->foo = "bar";
        $model = $reference->newFromBuilder($queryResultAttributes, 'eloquent');
        $this->assertInstanceOf(Textarea::class, $model->foo);
        $this->assertEquals('bar', $model->foo->get());
    }

    /** @test */
    public function can_set_repository_name_from_table_name()
    {
        $model = new EloquentModel();
        $this->assertSame('test', $model->getRepositoryName());
    }
}
