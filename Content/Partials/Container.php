<?php

namespace Kabas\Content\Partials;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;
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
            $item = $this->loadItem($part);
            $this->items[$part] = $this->makeItem($item);
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
            return realpath($this->path . DS . $file . '.json');
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

      //    TODO :
      //    All the following should move to BaseItem
      //    and be supported on each content type.

      protected function loadItem($id)
      {
            $file = $this->getFile($id);
            if($file) return $this->loadFromContent($file);
            // The content file was not found, we'll have to check
            // if the controller exists
            $controller = $this->getController($id);
            if($controller) return $this->loadFromController($id, $controller);
      }

      protected function loadFromContent($file)
      {
            $file = File::loadJson($file);
            $file->controller = $this->getController($file->template);
            return $file;
      }

      protected function loadFromController($id, $controller)
      {
            $file = new \stdClass();
            $file->id = $id;
            $file->controller = $controller;
            $ref = new \ReflectionClass($file->controller);
            if($ref->getStaticPropertyValue('template')){
                  $file->template = $ref->getStaticPropertyValue('template');
                  return $file;
            }
      }

      protected function getController($id)
      {
            $c = '\Theme\\' . App::theme() .'\Partials\\' . Text::toNamespace($id);
            if(class_exists($c)) return $c;
      }
}
