<?php

namespace Kabas\Database\Json\Runners\Operators\Expressions;

interface ExpressionInterface
{
    public function toType(string $type, bool $fallbackToString = false);
    public function toString();
}
