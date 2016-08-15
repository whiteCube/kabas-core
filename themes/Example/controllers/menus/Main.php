<?php

namespace Theme\Example\Menus;

use Kabas\Controller\MenuController;

class Main extends MenuController
{
      /**
       * In this function you get the chance to process the data
       * inside $this->items and $this->options before it is
       * passed on to the view.
       * @return void
       */
      protected function setup()
      {
            // Remove the current page from menu
            foreach ($this->items as $i => $item) {
                  if($item->isActive()) $this->items->remove($item);
            }
      }
}
