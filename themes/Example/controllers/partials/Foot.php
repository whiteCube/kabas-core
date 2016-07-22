<?php

namespace Theme\Example\Partials;

use \Kabas\Controller\PartialController;

class Foot extends PartialController
{
      
      /**
       * If defined, this function will be called automatically.
       * This is where you can process the menu data Kabas has
       * prepared by accessing $this->items and $this->options.
       * @return void
       */

      static $template = 'foot';

      protected function setup()
      {
      }

}
