<?php

namespace Kabas\Config\Settings;

use \Kabas\App;

class Container
{
      public $database;

      public $site;

      public $social;

      public function __construct()
      {
            $this->database = $this->load('database.php');
            $this->site = $this->load('site.php');
            $this->social = $this->load('social.php');
      }

      /**
       * Returns an object containing a config file
       * @return object
       */
      protected function load($file)
      {
            return $this->toObject(include(CONFIG_PATH . DS . $file));
      }

      /**
       * Turns an array to a stdClass recursively
       * @return object
       */
      protected function toObject($a)
      {
            $o = new \stdClass();
            foreach ($a as $key => $value) {
                  if(is_numeric($key)) return $a;
                  if(is_array($value)) $o->$key = $this->toObject($value);
                  else $o->$key = $value;
            }
            return $o;
      }

}
