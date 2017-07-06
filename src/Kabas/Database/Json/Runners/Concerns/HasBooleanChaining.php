<?php

namespace Kabas\Database\Json\Runners\Concerns;

trait HasBooleanChaining
{
    protected $boolean;

    protected function setBooleanMode($boolean) {
        $this->boolean = (strtoupper($boolean) == 'AND');
    }

    protected function isAndBoolean() {
        return $this->boolean;
    }

    protected function applyChaining($stack, $filtered = null) {
        if($this->isAndBoolean()) {
            return $this->run($filtered ?? $stack);
        }
        return $this->mergeResults($filtered, $this->run($stack));
    }

    protected function mergeResults(array $base, array $append) {
        return ($base + $append);
    }
}
        