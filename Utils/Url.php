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
      static function to($pageID, $params = [], $lang = null)
      {
            $route = App::router()->getRouteById($pageID);
            if (!$route) {
                  throw new \Exception('Page does not exist');
            }
            $params = self::makeParams($params, $route);

            return self::base() . self::getUrlLangString($lang) . self::fillRouteWithParams($route, $params);

      }

      protected static function getUrlLangString($lang)
      {
            if($lang) return '/' . $lang;
            else if(App::router()->hasLangInUrl) return '/' . App::config()->settings->site->lang->active;
            else return '';
      }

      protected static function fillRouteWithParams($route, $params)
      {
            $str = $route->string;
            foreach($route->getParameters() as $parameter){
                  if($parameter->isRequired && !array_key_exists($parameter->variable, $params)){
                        // TODO: Exception
                        echo 'error'; die();
                  } else if(array_key_exists($parameter->variable, $params)) {
                        $str = str_replace($parameter->string, $params[$parameter->variable], $str);
                  } else {
                        $str = str_replace($parameter->string, '', $str);
                  }
            }
            return $str;
      }

      protected static function makeParams($params, $route)
      {
            if(!is_array($params)) $params = [$route->getParameters()[0]->variable => $params];
            return $params;
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
            return $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
      }
}
