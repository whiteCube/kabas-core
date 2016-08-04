<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
      public $directory = 'menus';

      public $items;

      protected function setData($data)
      {
            $this->items = isset($data->items) ? $data->items : false;
      }

      protected function getTemplateNamespace()
      {
            return '\\Theme\\' . App::theme() .'\Menus\\' . parent::getTemplateNamespace();
      }

      protected function findControllerClass()
      {
            if($class = parent::findControllerClass()) return $class;
            return \Kabas\Controller\MenuController::class;
      }
}
