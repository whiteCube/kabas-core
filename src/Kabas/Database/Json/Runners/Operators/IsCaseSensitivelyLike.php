<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Operators\Expressions\CaseSensitiveLike;

class IsCaseSensitivelyLike extends IsLike implements OperatorInterface
{
    /**
     * Returns an instance of the "LIKE" expression parser
     * @param string $expression
     * @return Kabas\Database\Json\Runners\Operators\Expressions\CaseSensitiveLike
     */
    protected function makeRegexFromExpression($expression)
    {
        return new CaseSensitiveLike($expression);
    }
}