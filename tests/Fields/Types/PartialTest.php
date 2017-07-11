<?php 

namespace Tests\Fields\Types;

use Tests\CreatesApplication;
use Kabas\Fields\Types\Partial;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\NotFoundException;

class PartialTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
    }

    public function createProperPartial()
    {
        $data = new \stdClass;
        $data->label = 'Partial';
        $data->type = 'partial';
        $data->option = 'test';
        $field1 = new \stdClass;
        $field1->label = 'Title';
        $field1->type = 'text';
        $data->options = (object) ['title' => $field1];
        $partial = new Partial('Partial', null, $data);
        $partial->set((object) ['title' => 'My foo title']);
        return $partial;
    }

    public function createPartial($option)
    {
        $data = new \stdClass;
        $data->label = 'Partial';
        $data->type = 'partial';
        $data->option = $option;
        $field1 = new \stdClass;
        $field1->label = 'Title';
        $field1->type = 'text';
        $data->options = (object) ['title' => $field1];
        $partial = new Partial('Partial', null, $data);
        $partial->set((object) ['title' => 'My foo title']);
        return $partial;
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $partial = $this->createProperPartial();
        $this->assertInstanceOf(Partial::class, $partial);
    }

    /** @test */
    public function can_be_rendered()
    {
        $partial = $this->createPartial('test');
        $this->expectOutputString('<h2>My foo title</h2>');
        $partial->render();
    }

    /** @test */
    public function throws_exception_if_partial_does_not_exist()
    {
        $this->expectException(NotFoundException::class);
        $this->createPartial('baz');
    }
    
}