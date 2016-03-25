<?php

namespace Kabas\Utils;

use \Kabas\App;

class Url
{
      /**
       * Get the URI to the desired page
       * @param  string $pageID
       * @return string
       */
      static function to($pageID)
      {
            foreach(App::router()->routes as $route => $id) {
                  if($pageID === $id) {
                        if(App::router()->hasLangInUrl) $lang = '/' . App::config()->settings->site->lang->active;
                        else $lang = '';
                        return self::base() . $lang . $route;
                  }
            }
      }

      /**
       * Generate an URL to the current page in another language.
       * @param  string $lang
       * @return string
       */
      static function lang($lang)
      {
            return self::base() . '/' . $lang . App::router()->route;
      }

      /**
       * Get the base url of the site
       * @return string
       */
      static function base()
      {
            return $baseUrl = 'http://' . $_SERVER['SERVER_NAME'] . explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
      }
}
