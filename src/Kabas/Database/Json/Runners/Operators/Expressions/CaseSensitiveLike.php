<?php

namespace Kabas\Database\Json\Runners\Operators\Expressions;

class CaseSensitiveLike extends Like
{
    public function toRegex()
    {
        return '/^' . $this->parsed . '$/';
    }
}