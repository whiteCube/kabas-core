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
       * @return object
       */
      protected function makeItem($file)
      {
            return $file;
      }

}
