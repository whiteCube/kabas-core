<?php

namespace Kabas\Utils;

use Kabas\App;

class Meta
{
      /**
       * Set a meta information about the page
       * @param string $key
       * @param string $value
       */
      static function set($key, $value)
      {
            $page = App::config()->pages->items[App::router()->getCurrentPageID()];
            $page->meta->$key = $value;
      }

      /**
       * Get a meta information about the page
       * @param  string $key
       * @return string
       */
      static function get($key)
      {
            $page = App::config()->pages->items[App::router()->getCurrentPageID()];
            if(isset($page->meta->$key)) return $page->meta->$key;
            return null;
      }
}
