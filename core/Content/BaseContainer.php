<?php

namespace Kabas\Content;

use \Kabas\App;
use \Kabas\Utils\File;

class BaseContainer
{
      public $items = [];

      /**
       * path to the JSON content files
       * @var string
       */
      protected $path;

      public function __construct()
      {
            $this->path = $this->getPath();
            $this->loop(File::loadJsonFromDir($this->path));
      }

      /**
       * Check if item exists
       * @param  string $id
       * @return boolean
       */
      public function has($id)
      {
            if(array_key_exists($id, $this->items)) return true;
            return false;
      }

      /**
       * Get item if it exists
       * @param  string $part
       * @return object
       */
      public function get($id)
      {
            if($this->has($id)) return $this->items[$id];
            return false;
      }

      /**
       * Returns path to content files
       * @return string
       */
      protected function getPath()
      {
            return CONTENT_PATH . DS . App::router()->lang;
      }

      /**
       * Recursively go through the files array to instanciate pages
       * @param  array $files
       * @return void
       */
      protected function loop($files)
      {
            foreach($files as $file) {
                  if(is_array($file)) $this->loop($file);
                  else $this->items[$file->id] = $this->makeItem($file);
            }
      }

      /**
       * returns an item the container should store
       * @param  object $file
       * @return object
       */
      protected function makeItem($file)
      {
            return $file;
      }

}
