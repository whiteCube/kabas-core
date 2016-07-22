<?php

namespace Kabas\Content\Partials;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{
      public function __construct()
      {
            $this->path = $this->getPath();
      }

      /**
       * Load the specified part into memory.
       * @param  string $part
       * @return object
       */
      public function load($part)
      {
            $file = File::loadJson($this->getFile($part));
            $this->items[$part] = $this->makeItem($file);
            return $this->get($part);
      }

      /**
       * Returns path to partials directory
       * @return string
       */
      protected function getPath()
      {
            return parent::getPath() . DS . 'partials';
      }

      /**
       * Returns path to partial JSON file
       * @param  string $file
       * @return string
       */
      protected function getFile($file)
      {
            return $this->path . DS . $file . '.json';
      }

      /**
       * returns an item the container should store
       * @param  object $file
       * @return object
       */
      protected function makeItem($file)
      {
            return App::getInstance()->make('\Kabas\Content\Partials\Item', [$file]);
      }
}
