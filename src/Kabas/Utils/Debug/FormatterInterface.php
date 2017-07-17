<?php 

namespace Kabas\Utils\Debug;

interface FormatterInterface
{
    /**
     * Formats the backtrace stack
     * @param array $stack
     * @return mixed
     */
    public function format($stack);
}