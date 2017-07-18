<?php

namespace Tests\Database\Json\Runners\Operators;

use PHPUnit\Framework\TestCase;
use Kabas\Database\Json\Runners\Operators\IsBetween;

class IsBetweenTest extends TestCase
{
    public function setUp()
    {
        if(!defined('DEBUG')) define('DEBUG', true);
    }

    /** @test */
    public function cannot_instanciate_isBetween_from_non_array()
    {
        $this->expectException(\TypeError::class);
        $operator = new IsBetween('foo', 'number');
    }

    /** @test */
    public function cannot_instanciate_isBetween_from_incomplete_array()
    {
        $this->expectException(\Kabas\Database\Json\Runners\Exceptions\ExpressionConversionException::class);
        $operator = new IsBetween([1], 'number');
    }

    /** @test */
    public function can_instanciate_and_compare_digits_isBetween()
    {
        $operator = new IsBetween([1,5], 'number');
        $this->assertFalse($operator->compare(0));
        $this->assertTrue($operator->compare(1));
        $this->assertTrue($operator->compare(3));
        $this->assertTrue($operator->compare(5));
        $this->assertFalse($operator->compare(9));
    }

    /** @test */
    public function can_instanciate_and_compare_date_isBetween()
    {
        $operator = new IsBetween(['2017-03-16 14:30:20', '2017-08-19 07:12:24'], 'date');
        $this->assertFalse($operator->compare('2016-03-16 12:40:12'));
        $this->assertTrue($operator->compare('2017-03-16 14:30:20'));
        $this->assertTrue($operator->compare('2017-05-16 14:30:20'));
        $this->assertTrue($operator->compare('2017-08-19 07:12:24'));
        $this->assertFalse($operator->compare('2018-08-19 07:12:24'));
    }

    /** @test */
    public function cannot_compare_date_with_digit_isBetween()
    {
        $this->expectException(\Kabas\Database\Json\Runners\Exceptions\InvalidExpressionException::class);
        $operator = new IsBetween(['2017-03-16 14:30:20', '2017-08-19 07:12:24'], 'date');
        $operator->compare(5);
    }

    /** @test */
    public function can_convert_isBetween_to_string()
    {
        $operator = new IsBetween([1, 8], 'number');
        $this->assertEquals('BETWEEN 1 AND 8', $operator->getExpressionString());
    }

    /** @test */
    public function can_get_isBetween_name()
    {
        $operator = new IsBetween([1, 8], 'number');
        $this->assertEquals('IsBetween', $operator->getName());
    }
}