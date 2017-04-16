<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseContainer;

class LinksContainer extends BaseContainer implements \IteratorAggregate
{
      public $items = [];

      protected $structure;

      public function __construct($items = [], $structure = null)
      {
            $this->structure = $structure;
            if(is_array($items)) $this->makeItems($items);
      }

      public function make()
      {
            foreach ($this->items as $item) {
                  $item->make();
                  if($item->hasSub()) $item->getSub()->make();
            }
      }

      public function add($item)
      {
            $this->items[] = $this->makeItem($item);
      }

      public function remove($item)
      {
            $id = $item;
            if(is_a($item, \Kabas\Content\Menus\LinkItem::class)) $id = $item->id;
            foreach ($this->items as $key => $link) {
                  if($link->id === $id) unset($this->items[$key]);
            }
      }

      protected function makeItems($items)
      {
            foreach ($items as $item) {
                  $this->add($item);
            }
      }

      protected function makeItem($item)
      {
            return App::getInstance()->make('\\Kabas\\Content\\Menus\\LinkItem', [$item, count($this->items), $this->structure]);
      }

      /**
       * Makes item iterable
       * @return array
       */
      public function getIterator()
      {
            return new \ArrayIterator($this->items);
      }
}
