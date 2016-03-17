<?php

namespace Kabas\Utils;

use Kabas\View\View;

class Assets
{

      private static $required = [];

      static function required($location, $path)
      {
            self::$required[$location][] = $path;
      }

      static function here($location)
      {
            echo "@@@ASSETS-" . $location . '@@@';
      }

      static function load($page)
      {
            $pattern = '/@@@ASSETS-(.+?)@@@/';
            preg_match_all($pattern, $page, $matches);

            $assets = [];
            foreach($matches[1] as $location) {
                  if(isset(self::$required[$location])) {
                        foreach(self::$required[$location] as $asset) {
                              if(isset($assets[$location])) {
                                    $asset = self::generateTag($asset);
                                    $assets[$location] = $assets[$location] . $asset;
                              } else {
                                    $asset = self::generateTag($asset);
                                    $assets[$location] = $asset;
                              }
                        }
                  } else {
                        $assets[$location] = '';
                  }
            }
            for($i = 0; $i < count($matches[0]); $i++) {
                  $pattern = '/' . $matches[0][$i] . '/';
                  $name = $matches[1][$i];
                  $page = preg_replace($pattern, $assets[$name], $page);
            }

            return $page;

      }

      static function generateTag($asset)
      {
            $tag = $asset;
            return $tag;
      }
}
