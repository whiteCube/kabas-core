<?php

namespace Kabas\Database\Json\Runners\Operators;

interface OperatorInterface
{
    public function compare($value) : bool;
}
