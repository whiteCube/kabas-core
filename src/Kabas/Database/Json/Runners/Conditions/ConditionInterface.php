<?php

namespace Kabas\Database\Json\Runners\Conditions;

interface ConditionInterface
{
    public function apply($stack, $filtered = null) : array;
    public function run($stack) : array;
}
