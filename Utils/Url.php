<?php

namespace Kabas\Utils;

use \Kabas\App;
use \Kabas\Utils\Assets;
use \Kabas\Utils\Lang;

class Url
{
      /**
       * Get the URI to the desired page
       * @param  string $id
       * @param  array $params (optionnal)
       * @param  string $lang (optionnal)
       * @return string
       */
      static function to($id, $params = [], $lang = null)
      {
            $route = App::router()->getRouteByPage($id);
            if (!$route) throw new \Exception('Page does not exist');
            $params = self::makeParams($params, $route);
            return self::base() . self::getUrlLangString($lang) . self::fillRouteWithParams($route, $params);

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
            return trim(App::router()->getBase(), '/');
      }

      /**
       * Get the URL to given public asset in active theme
       * @return string
       */
      static function asset($path)
      {
            return Assets::src($path);
      }

      /**
       * Get the URL for given path
       * @param  string $path
       * @return string
       */
      static function fromPath($path)
      {
            if(strpos($path, BASE_PATH) !== 0) return false;
            $path = trim(str_replace(DS, '/', substr($path, strlen(BASE_PATH))), '/');
            return self::base() . '/' . $path;
      }

      /**
       * Returns an URL-clean version of the language
       * @param  string $lang
       * @return string
       */
      static function getUrlLang($lang)
      {
            $lang = Lang::is($lang);
            if($lang && $lang !== Lang::getDefault()) return Lang::alias($lang);
            return false;
      }

      /**
       * Returns Kabas-parsed URL
       * @param  string $url
       * @return object
       */
      static function parse($url)
      {
            return App::router()->parseUrl($url);
      }

      /**
       * Returns route found in URL
       * @param  string $url
       * @return object
       */
      static function route($url)
      {
            return App::router()->extractRoute($url);
      }

      protected static function getUrlLangString($lang){
            if($lang = self::getUrlLangAlias($lang)) return '/' . $lang;
            return '';
      }

      protected static function getUrlLangAlias($lang)
      {
            if($lang) return self::getUrlLang($lang);
            return self::getUrlLang(App::router()->lang);
      }

      protected static function fillRouteWithParams($route, $params)
      {
            $str = $route->string;
            foreach($route->parameters as $parameter){
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
            if(!is_array($params)) $params = [$route->parameters[0]->variable => $params];
            return $params;
      }
}
