<?php

namespace Kabas\Utils;

use Kabas\View\View;

class Assets
{
      /**
       * Keeps track of all required assets
       * @var array
       */
      private static $required = [];

      /**
       * Adds an asset dependency.
       * @param  string $location Where the asset will be loaded
       * @param  string $path     Path of the asset
       * @return void
       */
      static function required($location, $path)
      {
            self::$required[$location][] = $path;
      }

      /**
       * Adds a marker to pinpoint the location of where
       * the assets will be loaded
       * @param  string $location
       * @return void
       */
      static function here($location)
      {
            echo "@@@ASSETS-" . $location . '@@@';
      }

      /**
       * Takes the page buffer and loads the assets at the marked
       * positions.
       * @param  string $page The buffered page
       * @return string       The buffered page with the assets loaded
       */
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

      /**
       * Generate the proper tag for an asset.
       * @param  string $asset
       * @return string
       */
      static function generateTag($asset)
      {
            $tag = $asset;
            return $tag;
      }
}
