<?php

namespace Kabas\Utils;

use Kabas\App;

class Page
{
      /**
       * Get the title of the current page
       * @return string
       */
      static function title()
      {
            return App::content()->pages->getCurrent()->title;
      }

      /**
       * Get the identifier of the current page
       * @return string
       */
      static function id()
      {
            return App::content()->pages->getCurrent()->id;
      }
}
