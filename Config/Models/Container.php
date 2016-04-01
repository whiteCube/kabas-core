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
            $this->items[$modelInfo->filename] = [];
            $this->loadModelFields($modelInfo);
      }

      /**
       * Read the fields for the specified part and load them into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModelFields($modelInfo)
      {
            $file = File::loadJsonFromDir($modelInfo->path);
            $this->items[$modelInfo->filename] = $file[0]->fields;
      }

}
