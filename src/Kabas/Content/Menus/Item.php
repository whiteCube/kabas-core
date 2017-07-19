<?php

namespace Kabas\Content\Menus;

use Kabas\App;
use Kabas\Utils\File;
use Kabas\Content\BaseItem;
use Kabas\Content\Menus\Links;

class Item extends BaseItem
{
    public $directory = 'menus';

    public $items;

    public function parse()
    {
        parent::parse();
        $this->items->parse();
    }

    protected function loadStructure()
    {
        if(!is_null($this->structure)) return;
        $this->structure = File::loadJson($this->getStructureFile());
    }

    public function getMenuItems()
    {
        return $this->items;
    }

    protected function setData($data)
    {
        $this->items = new Links($data->items ?? null, $this->structure->item ?? null);
    }

    protected function getTemplateNamespace()
    {
        return '\\Theme\\' . App::themes()->getCurrent('name') .'\\Menus\\' . parent::getTemplateNamespace();
    }

    protected function findControllerClass()
    {
        if($class = parent::findControllerClass()) return $class;
        return \Kabas\Controller\MenuController::class;
    }
}
