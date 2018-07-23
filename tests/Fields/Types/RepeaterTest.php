<?php 

namespace Tests\Fields\Types;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Fields\Types\Repeater;

class RepeaterTest extends TestCase
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
        $data = new \stdClass;
        $data->label = 'Repeater';
        $data->type = 'repeater';

        $data->option = (object) [
            "type" => "text",
            "label" => "Title"
        ];

        $val1 = new \stdClass;
        $val1->value = 'My foo title';
        $val2 = new \stdClass;
        $val2->value = 'My bar title';

        $this->repeater = new Repeater('Repeater', null, $data);
        $this->repeater->set([$val1, $val2]);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Repeater::class, $this->repeater);
    }
    
}