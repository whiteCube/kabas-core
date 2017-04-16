<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
      public $directory = 'menus';

      public $items;

      public function parse()
      {
            parent::parse();
            $this->items->parse();
      }

      protected function setData($data)
      {
            $this->items = App::getInstance()->make('\\Kabas\\Content\\Menus\\LinksContainer', [@$data->items, @$this->structure->item]);
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
