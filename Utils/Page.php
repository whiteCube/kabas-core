<?php

namespace Kabas\Utils;

use Kabas\App;

class Page
{
      static function title()
      {
            $app = App::getInstance();
            $pageID = $app->router->getCurrentPageID();
            $pageTitle = $app->config->pages->items[$pageID]->title;
            return $pageTitle;
      }
}
