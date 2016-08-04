<?php

namespace Kabas\Http\Responses;

use Kabas\View\View as ViewEngine;
use Kabas\Http\Response;

class View extends Response
{
      public function __construct($view, $item)
      {
            $this->view = $view;
            $this->item = $item;
      }

      /**
       * Executes the response. Called automatically.
       * @return void
       */
      public function run()
      {
            $this->setHeaders();
            ViewEngine::make($this->view, $this->getData(), $this->item->directory);
      }

      protected function getData()
      {
            $o = $this->item->fields;
            if(is_null($o)) $o = new \stdClass();
            if(get_class($this->item) == 'Kabas\Content\Menus\Item'){
                  $o->items = $this->item->items;
            }
            return $o;
      }
}
