<?php

namespace Kabas\Database\Json\Runners\Operators;

interface OperatorInterface
{
    public function compare($value) : bool;
    public function getName();
    public function getType();
    public function getExpressionString();
}
