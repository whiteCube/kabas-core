<?php

namespace Tests\Database\Json\Runners\Operators;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Kabas\Database\Json\Runners\Operators\IsEqualTo;

class IsEqualToTest extends TestCase
{
    public function setUp()
    {
        if(!defined('DEBUG')) define('DEBUG', true);
    }

    /** @test */
    public function cannot_instanciate_IsEqualTo_from_non_string()
    {
        $this->expectException(\Kabas\Database\Json\Runners\Exceptions\ExpressionTypeException::class);
        $operator = new IsEqualTo(['foo','bar'], 'string');
    }

    /** @test */
    public function can_convert_IsEqualTo_expression_to_string()
    {
        $operator = new IsEqualTo('2017-07-12', 'date');
        $this->assertEquals('2017-07-12', $operator->getExpressionString());
    }

    /** @test */
    public function can_compare_IsEqualTo_string()
    {
        $operator = new IsEqualTo('foo', 'string');
        $this->assertTrue($operator->compare('foo'));
        $this->assertTrue($operator->compare('Foo'));
        $this->assertFalse($operator->compare('Bar'));
    }

    /** @test */
    public function can_compare_IsEqualTo_number()
    {
        $operator = new IsEqualTo(14, 'number');
        $this->assertTrue($operator->compare(14));
        $this->assertTrue($operator->compare(14.000));
        $this->assertTrue($operator->compare('14'));
        $this->assertTrue($operator->compare('14.000'));
        $this->assertFalse($operator->compare(12));
        $this->assertFalse($operator->compare(14.001));
        $this->assertFalse($operator->compare('13'));
    }

    /** @test */
    public function can_compare_IsEqualTo_dates()
    {
        $operator = new IsEqualTo('2017-07-24 16:45:30', 'date');
        $this->assertTrue($operator->compare('2017-07-24 16:45:30'));
        $this->assertTrue($operator->compare(new Carbon('2017-07-24 16:45:30')));
        $this->assertFalse($operator->compare('2017-07-24 16:45:31'));
        $this->assertFalse($operator->compare(new Carbon('2017-07-24 16:45:29')));
    }
}