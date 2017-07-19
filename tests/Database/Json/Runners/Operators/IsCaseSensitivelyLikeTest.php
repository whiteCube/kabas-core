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
}