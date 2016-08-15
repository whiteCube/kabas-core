<?php

namespace Kabas\Controller;

use Kabas\View\View;
use Kabas\App;

class MenuController extends BaseController
{
      protected $type = 'menu';

      public function __construct($view)
      {
            $this->item = $view;
            $this->item->items->make();
            $response = $this->setup();
            if(is_null($response)){
                  $response = $this->view($this->getTemplateName(), false);
            }
            App::response()->send($response);
      }

      /**
       * Default menu setup.
       * @return void
       */
      protected function setup()
      {
            return null;
      }
}
