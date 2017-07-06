<?php

namespace Kabas\Database\Json\Runners\Operators\Expressions;

class Like
{
    protected $escape;

    protected $parsed;

    /**
     * Makes a new "LIKE" Operator
     * @param string $expression
     * @return void
     */
    public function __construct($expression, $escape = '\\') {
        $this->escape = preg_quote($escape, '/');
        $this->parsed = $this->parse($expression, $escape);
    }

    public function toRegex()
    {
        return '/^' . $this->parsed . '$/i';
    }

    protected function parse($expression, $originalEscape)
    {
        $regex = '';
        $hasJustAddedWildcard = false;
        foreach ($this->split($expression) as $segment) {
            if($segment == '%' && $hasJustAddedWildcard) continue; 
            $regex .= $this->translateSegment($segment, $originalEscape);
            $hasJustAddedWildcard = ($segment == '%');
        }
        return $regex;
    }

    protected function split($expression)
    {
        $pattern = '/((?:' . $this->escape . ')?(?:' . $this->escape . '|%|_))/';
        return preg_split($pattern, $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    protected function translateSegment($segment, $originalEscape)
    {
        if($segment == '%') return '.*?';
        if($segment == '_') return '.';
        if($segment == $originalEscape.$originalEscape) return $this->escape;
        if($segment == $originalEscape.'%') return '%';
        if($segment == $originalEscape.'_') return '_';
        return preg_quote($segment, '/');
    }
}