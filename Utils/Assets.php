<?php

namespace Kabas\Utils;

use Kabas\View\View;
use Kabas\App;

class Assets
{
      /**
       * Keeps track of all required assets
       * @var array
       */
      protected static $required = [];

      /**
       * Adds an asset dependency.
       * @param  string $location Where the asset will be loaded
       * @param  string $path     Path of the asset
       * @return void
       */
      static function add($location, $path)
      {
            if(!isset(self::$required[$location])) {
                  self::$required[$location] = [];
            }
            if(!in_array($path, self::$required[$location])){
                  self::$required[$location][] = $path;
            }
      }

      /**
       * Add a marker to pinpoint the location where
       * the assets will be loaded.
       * @param  string $location
       * @return void
       */
      static function here($location)
      {
            echo "@@@ASSETS-" . $location . '@@@';
      }

      /**
       * Takes the page buffer and loads the assets at the marked positions.
       * @param  string $page The buffered page
       * @return string       The buffered page with the assets loaded
       */
      static function load($page)
      {
            $pattern = '/@@@ASSETS-(.+?)@@@/';
            preg_match_all($pattern, $page, $matches);
            foreach($matches[1] as $location) {
                  if(!isset(self::$required[$location])) { $assets[$location] = ''; break; }
                  foreach(self::$required[$location] as $asset) {
                        if(!isset($assets[$location])) { $assets[$location] = ''; }
                        $asset = self::generateTag($asset);
                        $assets[$location] = $assets[$location] . $asset;
                  }
            }
            if(!empty($assets)) $page = str_replace($matches[0], $assets, $page);
            return $page;
      }

      /**
       * Generate the proper tag for an asset.
       * @param  string $asset
       * @return string
       */
      protected static function generateTag($asset)
      {
            $type = self::getType($asset);

            switch($type) {
                  case 'css':
                        $assetPath = self::getDir('css') . $asset;
                        $tag = '<link rel="stylesheet" type="text/css" href="' . $assetPath . '" />';
                        break;
                  case 'js':
                        $assetPath = self::getDir('js') . $asset;
                        $tag = '<script src="' . $assetPath . '"></script>';
                        break;
                  default:
                        return;
            }

            return $tag;
      }

      /**
       * Get the type of the asset from its extension.
       * @param  string $asset
       * @return string
       */
      protected static function getType($asset)
      {
            $exploded = explode('.', $asset);
            $index = count($exploded) - 1;
            return $exploded[$index];
      }

      /**
       * Get the full path for an asset directory
       * @param  string $dir
       * @return string
       */
      protected static function getDir($dir)
      {
            $dir =
                  App::router()->baseUrl
                  . DS
                  . 'themes'
                  . DS
                  . App::theme()
                  . DS
                  . 'assets'
                  . DS
                  . $dir
                  . DS;

            return $dir;
      }
}
