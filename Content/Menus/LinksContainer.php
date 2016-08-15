<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseContainer;

class LinksContainer extends BaseContainer implements \IteratorAggregate
{
      public $items = [];

      protected $structure;

      public function __construct($items, $structure)
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

      protected function makeItems($items)
      {
            foreach ($items as $item) {
                  $this->items[] = $this->makeItem($item);
            }
      }

      protected function makeItem($item)
      {
            return App::getInstance()->make('\Kabas\Content\Menus\LinkItem', [$item, $this->structure]);
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
