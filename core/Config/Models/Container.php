<?php

namespace Kabas\Config\Models;

use \Kabas\Utils\File;
use Kabas\App;

class Container
{
      /**
       * Check if part exists in content
       * @param  string  $partID
       * @return boolean
       */
      public function hasPart($partID)
      {
            if(array_key_exists($partID, $this->items)) return true;
            return false;
      }

      /**
       * Get part if it exists
       * @param  string $partID
       * @return object
       */
      public function getPart($partID)
      {
            if($this->hasPart($partID)) return $this->items[$partID];
            else return 'error, part does not exist';
      }

      /**
       * Load the specified part into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModel($modelID)
      {
            $this->items[$modelID] = [];
            $this->loadModelFields($modelID);
      }

      /**
       * Read the fields for the specified part and load them into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModelFields($modelID)
      {
            $path = THEME_PATH . DS . 'models' . DS . $modelID;
            $file = File::loadJsonFromDir($path);
            $this->items[$modelID] = $file[0]->fields;
      }

}
