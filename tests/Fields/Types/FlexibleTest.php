<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Text;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Flexible;
use Kabas\Exceptions\TypeException;

class FlexibleTest extends TestCase
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
            'title' => $opt1,
            'background' => $opt2
        ];

        $val1 = new \stdClass;
        $val1->option = 'title';
        $val1->value = 'My foo title';
        $val1->class = 'Title';

        $val2 = new \stdClass;
        $val2->option = 'background';
        $val2->value = '#fefefe';

        $this->flexible = new Flexible('Flexible', null, $data);
        $this->flexible->set([$val1, $val2]);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Flexible::class, $this->flexible);
    }

    /** @test */
    public function can_find_an_element()
    {
        $this->assertInstanceOf(Text::class, $this->flexible->find('title'));
        $this->assertNull($this->flexible->find('aaa'));
    }

}