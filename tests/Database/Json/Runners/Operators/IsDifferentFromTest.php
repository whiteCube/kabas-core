<?php

namespace Tests\Database\Json\Runners\Operators;

use PHPUnit\Framework\TestCase;
use Kabas\Database\Json\Runners\Operators\IsDifferentFrom;

class IsDifferentFromTest extends TestCase
{
    public function setUp()
    {
        if(!defined('DEBUG')) define('DEBUG', true);
    }

    /** @test */
    public function cannot_instanciate_IsDifferentFrom_from_non_string()
    {
        $this->expectException(\Kabas\Database\Json\Runners\Exceptions\ExpressionTypeException::class);
        $operator = new IsDifferentFrom(['foo','bar'], 'string');
    }

    /** @test */
    public function can_convert_IsDifferentFrom_expression_to_string()
    {
        $operator = new IsDifferentFrom('foo', 'string');
        $this->assertEquals('foo', $operator->getExpressionString());
    }

    /** @test */
    public function can_compare_IsDifferentFrom()
    {
        $operator = new IsDifferentFrom('foo', 'string');
        $this->assertTrue($operator->compare('Bar'));
        $this->assertFalse($operator->compare('foo'));
        $this->assertFalse($operator->compare('Foo'));
    }

    /** @test */
    public function can_compare_IsDifferentFrom_dates()
    {
        $operator = new IsDifferentFrom('2017-07-24 16:45:30', 'date');
        $this->assertTrue($operator->compare('2015-05-12 13:24:39'));
        $this->assertTrue($operator->compare('2017-07-24'));
        $this->assertFalse($operator->compare('2017-07-24 16:45:30'));
    }
}