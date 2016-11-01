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
            foreach(App::config()->settings->site->lang->available as $code => $alias){
                  if($code == $lang || $alias == $lang) return $code;
            }
            return false;
      }

      /**
       * Returns default lang code
       * @return string
       */
      static function getDefault()
      {
            return App::config()->settings->site->lang->default;
      }

      /**
       * Returns URL alias for given lang code
       * @return string
       */
      static function alias($lang)
      {
            $lang = self::is($lang);
            if($lang){
                  foreach(App::config()->settings->site->lang->available as $code => $alias){
                        if($code == $lang) return $alias;
                  }
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
      * Get the current short language code
      * @return string
      */
      static function short()
      {
            return substr(self::current(),0,2);
      }
}
