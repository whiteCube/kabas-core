<?php

namespace Tests\Fields\Types;

use Kabas\Fields\Groupable;
use Kabas\Fields\Types\Text;
use Kabas\Fields\Types\Group;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class GroupableTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $data = new \stdClass;
        $data->label = 'Group';
        $data->type = 'group';
        $field1 = new \stdClass;
        $field1->label = 'Title';
        $field1->type = 'text';
        $data->options = (object) ['title' => $field1];
        $this->group = new Group('Group', null, $data);
        $this->group->set((object) ['title' => 'My foo title']);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Groupable::class, $this->group);
    }

    /** @test */
    public function can_get_subfields()
    {
        $this->assertInstanceOf(Text::class, $this->group->title);
        $this->assertInstanceOf(Text::class, $this->group->title());
    }

    /** @test */
    public function can_propagate_setters_to_child_fields()
    {
        $this->group->title = 'My bar title';
        $this->assertInstanceOf(Text::class, $this->group->title);
        $this->assertSame('My bar title', (string) $this->group->title);
    }

    /** @test */
    public function can_set_values_that_did_not_exist()
    {
        $this->group->foo = 'bar';
        $this->assertSame('bar', $this->group->foo);
    }

}