<?php

namespace Theme\TheCapricorn\Templates;

use \Auth;
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
        \Kabas\Utils\Debug::backtrace();
        if(App::request()->isPost()) {
            Uploads::userfile()->save();
        }
    }
}
