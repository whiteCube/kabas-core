<?php

namespace Theme\TheCapricorn\Partials;

use Kabas\Http\Responses\Json;
use Kabas\Controller\PartialController;

class Calendar extends PartialController
{

    protected function setup()
    {
        return new Json(['test' => 'yo', 'foo' => ['bar', 'baz']]);
    }
}
