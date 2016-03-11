<?php

namespace Kabas\Utils;

class Url
{
      /**
       * Get the URI to the desired page
       * @param  string $pageID
       * @return string
       */
      static function to($pageID)
      {
            global $app;

            $baseUrl = $_SERVER['SERVER_NAME'] . explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];

            foreach($app->router->routes as $route => $id) {
                  if($pageID === $id) {
                        return 'http://'. $baseUrl . $route;
                  }
            }
      }
}
