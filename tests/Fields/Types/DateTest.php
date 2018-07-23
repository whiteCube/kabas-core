<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Date;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class DateTest extends TestCase
{

    public function setUp()
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $data = new \stdClass;
        $data->label = "Date";
        $data->type = "date";
        $this->date = new Date('Date', null, $data);
        $this->date->set('10 September 2000');
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Date::class, $this->date);
    }

    /** @test */
    public function can_make_use_of_carbon_methods()
    {
        $this->assertSame('2000-09-10', $this->date->toDateString());
    }

    /** @test */
    public function can_make_use_of_carbon_properties()
    {
        $this->assertSame(10, $this->date->day);
    }

    /** @test */
    public function can_pass_set_values_to_carbon_instance()
    {
        $this->assertSame($this->date->year, 2000);
        $this->date->year = 2018;
        $this->assertSame($this->date->year, 2018);
    }


}