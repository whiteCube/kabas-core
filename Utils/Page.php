<?php

namespace Kabas\Utils;

use Kabas\App;

class Page
{
      static function title()
      {
            $pageID = App::router()->getCurrentPageID();
            $pageTitle = App::config()->pages->items[$pageID]->title;
            return $pageTitle;
      }
}
