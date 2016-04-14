<?php

namespace Kabas\Utils;

use Kabas\App;

class Page
{
      /**
       * Get the title of the page
       * @return string
       */
      static function title()
      {
            $pageID = App::router()->getCurrentPageID();
            $pageTitle = App::config()->pages->items[$pageID]->title;
            return $pageTitle;
      }

      static function id()
      {
            return App::router()->getCurrentPageID();
      }
}
