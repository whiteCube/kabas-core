<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Aggregate;
use Kabas\Fields\Types\Text;
use Kabas\Fields\Types\Group;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class AggregateTest extends TestCase
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
        $this->aggregate = new Group('Group', null, $data);
        $this->aggregate->set((object) ['title' => 'My foo title']);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Aggregate::class, $this->aggregate);
    }

    /** @test */
    public function can_be_echoed()
    {
        $this->expectOutputString('#multiple-fields');
        echo $this->aggregate;
    }

    /** @test */
    public function can_count_amount_of_items()
    {
        $this->assertSame(1, $this->aggregate->count());
    }

    /** @test */
    public function returns_null_when_getting_value_that_does_not_exist()
    {
        $this->assertNull($this->aggregate->get('foo'));
    }

    /** @test */
    public function can_override_single_aggregate_option()
    {
        $this->aggregate->setOption('title', 'foo');
        $this->assertSame('foo', $this->aggregate->title);
    }

}