<?php

namespace Kabas\Utils;

use \Kabas\App;

class Lang
{

      /**
      * Determines if string is an available lang
      * Returns lang code string or false
      * @param $lang
      * @return string
      */
      static function is($lang)
      {
            foreach(static::getAvailable() as $code => $info){
                  if($code == $lang || $info['slug'] == $lang) return $code;
            }
            return false;
      }

      /**
      * Returns default lang code
      * @return string
      */
      static function getDefault()
      {
            return static::is(App::config()->get('lang.default'));
      }

      /**
      * Returns available langs
      * @return object
      */
      static function getAvailable()
      {
            return App::config()->get('lang.available');
      }

      /**
      * Returns slug for given lang code
      * @return string
      */
      static function slug($lang = null)
      {
            if(is_null($lang)) $lang = static::current();
            if($lang = self::is($lang)){
                  if(isset(static::getAvailable()[$lang]['slug'])){
                        return static::getAvailable()[$lang]['slug'];
                  }
                  return $lang;
            }
            return false;
      }

      /**
      * Get the current language code
      * @return string
      */
      static function current()
      {
            return App::router()->lang;
      }

      /**
      * Returns array to create a menu
      * @return object
      */
      static function getMenu()
      {
            $langs = self::getAvailable();

            foreach ($langs as $code => $o) {
                  $o->url = Url::lang($o->alias);
                  $o->isActive = $code == self::current();
            }
            return $langs;
      }
}
