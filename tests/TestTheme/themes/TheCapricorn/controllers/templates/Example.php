<?php

namespace Theme\TheCapricorn\Templates;

use Kabas\App;
use Kabas\Utils\Uploads;
use Kabas\Controller\TemplateController;

class Example extends TemplateController
{
    /**
     * Handles controller tasks
     * @return void
     */
    protected function setup()
    {
        if(App::request()->isPost()) {
            Uploads::userfile()->save();
        }
    }
}
