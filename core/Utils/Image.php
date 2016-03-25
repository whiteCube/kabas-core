<?php

namespace Kabas\Utils;

use Kabas\App;

class Image
{
      static function show($o, $echo = true)
      {
            $s = '<img src="' . self::src($o) . '" />';
            if($echo) echo($s);
            return $s;
      }

      public static function src($o)
      {
            if(get_class($o) !== 'Kabas\Objects\Image\Item') $o = $o->file;
            return Url::base() . '/themes/' . App::config()->settings->site->theme . '/assets/img/' . $o->file;
      }

}
