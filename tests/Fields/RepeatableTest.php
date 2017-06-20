<?php 

namespace Tests\Fields;

use Kabas\Fields\Option;
use Tests\CreatesApplication;
use Kabas\Fields\Types\Select;
use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Checkbox;
use Kabas\Fields\Types\Flexible;

class RepeatableTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
        $data = new \stdClass;
        $data->label = 'Flexible';
        $data->type = 'flexible';

        $opt1 = new \stdClass;
        $opt1->label = 'title';
        $opt1->type = 'text';
        $opt2 = new \stdClass;
        $opt2->label = 'background';
        $opt2->type = 'color';
        $data->options = [
            $opt1,
            $opt2
        ];

        $val1 = new \stdClass;
        $val1->option = 0;
        $val1->value = 'My foo title';
        $val1->class = 'Title';
        $val2 = new \stdClass;
        $val2->option = 'background';
        $val2->value = '#fefefe';

        $this->repeatable = new Flexible('Flexible', null, $data);
        $this->repeatable->set([$val1, $val2]);
    }

    /** @test */
    public function can_format_a_value_to_be_used_with_repeatable_fields()
    {
        $this->assertFalse($this->repeatable->format('test'));
        $this->assertSame([], $this->repeatable->format(''));
        $val1 = new \stdClass;
        $val1->option = 0;
        $val1->value = 'My foo title';
        $val1->class = 'Title';
        $val2 = new \stdClass;
        $val2->option = 'background';
        $val2->value = '#fefefe';
        $this->assertCount(2, $this->repeatable->format([$val1, $val2]));
    }

    /** @test */
    public function can_determine_if_value_is_an_array()
    {
        $this->assertTrue($this->repeatable->condition());
    }

}