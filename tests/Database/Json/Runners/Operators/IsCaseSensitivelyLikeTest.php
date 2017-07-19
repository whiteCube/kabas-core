<?php

namespace Tests\Database\Json\Runners\Operators;

use PHPUnit\Framework\TestCase;
use Kabas\Database\Json\Runners\Operators\IsCaseSensitivelyLike;

class IsCaseSensitivelyLikeTest extends TestCase
{
    public function setUp()
    {
        if(!defined('DEBUG')) define('DEBUG', true);
    }

    /** @test */
    public function cannot_instanciate_isCaseSensitivelyLike_from_non_string()
    {
        $this->expectException(\Kabas\Database\Json\Runners\Exceptions\ExpressionTypeException::class);
        $operator = new IsCaseSensitivelyLike(['foo','bar'], 'string');
    }

    /** @test */
    public function can_convert_isCaseSensitivelyLike_expression_to_string()
    {
        $operator = new IsCaseSensitivelyLike('%Foo_!', 'string');
        $this->assertEquals('%Foo_!', $operator->getExpressionString());
    }

    /** @test */
    public function can_compare_isCaseSensitivelyLike()
    {
        $operator = new IsCaseSensitivelyLike('F_o%', 'string');
        $this->assertTrue($operator->compare('Foo'));
        $this->assertTrue($operator->compare('Fao bar'));
        $this->assertFalse($operator->compare('foo'));
        $this->assertFalse($operator->compare('bar Foo'));
    }

    /** @test */
    public function can_compare_isCaseSensitivelyLike_with_escaped_characters()
    {
        $operator = new IsCaseSensitivelyLike('%Foo\%\\\_!', 'string');
        $this->assertTrue($operator->compare('bar Foo%\f!'));
        $this->assertTrue($operator->compare('Foo%\ !'));
        $this->assertFalse($operator->compare('bar Foo%\!'));
        $this->assertFalse($operator->compare('bar foo%\f!'));
    }
}