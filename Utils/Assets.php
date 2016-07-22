<?php

namespace Kabas\Utils;

use \Kabas\App;

class Assets
{
      /**
       * Default location tag
       * @var string
       */
      protected static $placeholder = '<meta name="kabas-assets-location" value="%s">';

      /**
       * Keeps track of all required assets
       * @var array
       */
      protected static $required = [];

      /**
       * Adds an assets dependency.
       * @param  string $location
       * @param  string $src
       * @return void
       */
      public static function add($location, $src)
      {
            if(!isset(self::$required[$location])) self::$required[$location] = [];
            if(is_string($src)) self::pushToLocation($location, $src);
            else if(is_array($src)) {
                  foreach ($src as $item) {
                        self::add($location, $item);
                  }
            }
      }

      /**
       * Add a marker to pinpoint the location where
       * the assets will be loaded.
       * @param  string $location
       * @param  string $src (optional)
       * @return void
       */
      public static function here($location, $src = null)
      {
            echo self::getLocationTag($location) . PHP_EOL;
            if($src) self::add($location, $src);
      }

      /**
       * Takes the page buffer and loads the assets at the marked positions.
       * @param  string $page
       * @return string
       */
      public static function load($page)
      {
            preg_match_all(self::getLocationPattern(), $page, $matches);
            foreach($matches[1] as $i => $location) {
                  $page = self::includeLocation($location, self::getLocationAssets($location), $matches[0][$i], $page);
            }
            return $page;
      }

      /**
       * Adds a dependency to location
       * @param  string $location
       * @param  string $src
       * @return void
       */
      protected static function pushToLocation($location, $src)
      {
            if(!in_array($src, self::$required[$location])){
                  self::$required[$location][] = $src;
            }
      }

      /**
       * Generate the proper tag for an asset.
       * @param  object $asset
       * @return string
       */
      protected static function getTag($asset)
      {
            switch($asset->type) {
                  case 'css':
                        return '<link rel="stylesheet" type="text/css" href="' . $asset->src . '" />';
                        break;
                  case 'js':
                        return '<script type="text/javascript" src="' . $asset->src . '"></script>';
                        break;
                  default:
                        return '<link rel="icon" href="' . $asset->src . '" />';
                        break;
            }
      }

      /**
       * returns the asset's extension
       * @param  string $path
       * @return string
       */
      protected static function getType($path)
      {
            return strtolower(pathinfo($path, PATHINFO_EXTENSION));
      }

      /**
       * returns the asset's HREF
       * @param  string $src
       * @return string
       */
      protected static function getSrc($src)
      {
            $s = Url::base();
            $s .= 'themes/' . App::theme() . '/public/';
            $s .= $src;
            return $s;
      }

      /**
       * returns a location's placeholder tag
       * @param  string $location
       * @return string
       */
      protected static function getLocationTag($location)
      {
            return sprintf(self::$placeholder, $location);
      }

      /**
       * returns a generic regex pattern for placeholders
       * @return string
       */
      protected static function getLocationPattern()
      {
            $s = '/';
            $s .= sprintf(self::$placeholder, '(.+)?');
            return $s .= '/';
      }

      /**
       * returns all available assets for a location
       * @param  string $location
       * @return array
       */
      protected static function getLocationAssets($location)
      {
            $a = [];
            if(isset(self::$required[$location])){
                  foreach (self::$required[$location] as $src) {
                        if($src = self::getAsset($src)) array_push($a, $src);
                  }
            }
            return $a;
      }

      /**
       * returns an asset object if it exists
       * @param  string $src
       * @return object
       */
      protected static function getAsset($src)
      {
            $asset = new \stdClass();
            $asset->path = realpath(THEME_PATH . DS . 'public' . DS . $src);
            if(!$asset->path) return false;
            $asset->type = self::getType($asset->path);
            $asset->src = self::getSrc($src);
            $asset->tag = self::getTag($asset);
            return $asset;
      }

      /**
       * Replaces a location's placeholder with its registered assets.
       * Returns updated page
       * @param  string $location
       * @param  array $assets
       * @param  string $placeholder
       * @param  string $page
       * @return string
       */
      protected static function includeLocation($location, $assets, $placeholder, $page)
      {
            $page = str_replace($placeholder, self::getTagsString($assets), $page);
            return $page;
      }

      /**
       * returns all tags from assets array in one string
       * @param  array $assets
       * @return string
       */
      protected static function getTagsString($assets)
      {
            $s = '';
            foreach ($assets as $asset) {
                  $s .= $asset->tag . PHP_EOL;
            }
            return $s;
      }
}
