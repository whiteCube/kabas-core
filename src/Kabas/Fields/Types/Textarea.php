<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Textual;

class Textarea extends Textual
{

    protected function parse($value)
    {
        return nl2br($value);
    }
}
