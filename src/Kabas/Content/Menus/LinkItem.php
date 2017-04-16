<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class LinkItem extends BaseItem
{
      public $items;

      public $url;

      public $active;

      public function __construct($data, $id, $structure)
      {
            $this->id = $id;
            $this->structure = $structure;
            $this->fields = $this->loadFields($data);
            $this->controller = $this->findControllerClass();
            if(isset($data->items) && is_array($data->items)){
                  $this->items = App::getInstance()->make('\\Kabas\\Content\\Menus\\LinksContainer', [$data->items, $this->structure]);
            }
      }

      public function parse()
      {
            parent::parse();
            if($this->items) $this->items->parse();
      }

      protected function loadFields($data = null)
      {
            $fields = null;
            $data = $this->getFieldObject($data);
            if($this->structure) $fields = $this->getItemFields($data, $this->structure);
            $this->set($data);
            return $fields;
      }

      protected function findControllerClass()
      {
            if($class = parent::findControllerClass()) return $class;
            return \Kabas\Controller\MenuItem::class;
      }
}
