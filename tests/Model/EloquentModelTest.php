<?php 

namespace Tests\Model;

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
            'fields' => \Kabas\Fields\Container::class
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


}
