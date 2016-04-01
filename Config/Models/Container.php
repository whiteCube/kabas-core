<?php

namespace Kabas\Config\Models;

use \Kabas\Utils\File;
use Kabas\App;

class Container
{
      /**
       * Load the specified part into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModel($modelInfo)
      {
            if(!isset($this->items[$modelInfo->path->filename])) {
                  $this->items[$modelInfo->path->filename] = [];
                  $this->loadModelFields($modelInfo);
            }
      }

      /**
       * Read the fields for the specified part and load them into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModelFields($modelInfo)
      {
            $file = File::loadJsonFromDir($modelInfo->path->path);
            $this->items[$modelInfo->path->filename] = $file[0]->fields;
      }

      public function fieldExists($fieldName, $model)
      {
            return isset($this->items[$model]->$fieldName);
      }

}
