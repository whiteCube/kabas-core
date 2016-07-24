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
            $this->item->items = $this->makeItems($this->item->items);
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

      /**
       * Creates a MenuItem instance for each passed item and its sub-items
       * @param  array $items
       * @return array
       */
      protected function makeItems($items)
      {
            $nav = [];
            foreach($items as $item) {
                  $item = App::getInstance()->make('Kabas\Controller\MenuItem', [$item]);
                  if($item->hasSub()) {
                        $item->items = $this->makeItems($item->getSub());
                        $item->cleanSub();
                  }
                  $nav[] = $item;
            }
            return $nav;
      }

}
