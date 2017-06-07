<?php

namespace Kabas\Content\Partials;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
    public $directory = 'partials';

    protected function getTemplateNamespace()
    {
        return '\\Theme\\' . App::themes()->getCurrent('name') .'\\Partials\\' . parent::getTemplateNamespace();
    }

    protected function findControllerClass()
    {
        if($class = parent::findControllerClass()) return $class;
        return \Kabas\Controller\PartialController::class;
    }
}
