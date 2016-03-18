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
            $app = App::getInstance();

            $baseUrl = $_SERVER['SERVER_NAME'] . explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];

            foreach($app->router->routes as $route => $id) {
                  if($pageID === $id) {
                        if($app->router->hasLangInUrl) $lang = '/' . $app->config->settings->site->lang->active;
                        else $lang = '';
                        return 'http://'. $baseUrl . $lang . $route;
                  }
            }
      }

      static function lang($lang)
      {
            $app = App::getInstance();

            $baseUrl = $_SERVER['SERVER_NAME'] . explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
            return 'http://' . $baseUrl . '/' . $lang . $app->router->route;
      }
}
