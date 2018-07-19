<?php 

namespace Tests\Fields\Types;

use Kabas\Fields\Types\Number;
use PHPUnit\Framework\TestCase;
use Kabas\Exceptions\TypeException;

class NumberTest extends TestCase
{

    public function setUp()
    {
        $data = new \stdClass;
        $data->label = "Number";
        $data->type = "number";
        $this->number = new Number('number', $data);
        $this->number->set(4);
    }

    /** @test */
    public function can_be_instantiated_properly()
    {
        $this->assertInstanceOf(Number::class, $this->number);
    }

    /** @test */
    public function can_be_cast_as_int_or_float()
    {
        $this->assertTrue(is_int($this->number->int));
        $this->assertTrue(is_float($this->number->floatval()));
        $this->assertFalse($this->number->foo);
    }

    /** @test */
    public function can_be_set_with_a_string()
    {
        $this->number->set('5');
        $this->assertSame(5, $this->number->i);
    }

    /** @test */
    public function can_add_a_number_to_itself()
    {
        $this->number->add(5);
        $this->assertSame(9, $this->number->i);
    }

    /** @test */
    public function can_subtract_a_number_from_itself()
    {
        $this->number->subtract(3);
        $this->assertSame(1, $this->number->i);
    }

    /** @test */
    public function can_divide_itself_by_a_number()
    {
        $this->number->divide(2);
        $this->assertSame(2, $this->number->i);
    }

    /** @test */
    public function can_multiply_itself_by_a_number()
    {
        $this->number->multiply(3);
        $this->assertSame(12, $this->number->i);
    }

    /** @test */
    public function can_round_up()
    {
        $this->number->set(4.6);
        $this->assertSame(5, $this->number->ceil()->i);
    }

    /** @test */
    public function can_round_down()
    {
        $this->number->set(4.6);
        $this->assertSame(4, $this->number->floor()->i);
    }

    /** @test */
    public function can_be_formatted()
    {
        $this->number->set(123456);
        $this->assertSame('123,456', $this->number->formatted());
    }

    /** @test */
    public function can_be_money_formatted()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $this->number->set(1234.56);
        $this->assertStringStartsWith('USD', $this->number->money());
    }
}