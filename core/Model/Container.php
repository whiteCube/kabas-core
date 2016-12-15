<?php

namespace Kabas\Model;

use \Kabas\Utils\File;
use Kabas\App;

class Container
{
      /**
       * Load the model's fields
       * @param  object $info 
       * @return void
       */
      public function loadModel($info)
      {
            if(!isset($this->items[$info->name])) {
                  $this->loadModelFields($info);
            }
      }

      /**
       * Read the fields for the specified part and load them into memory.
       * @param  string $partID
       * @return void
       */
      public function loadModelFields($info)
      {
            $file = File::loadJson($info->structure);
            $this->items[$info->name] = is_object($file) ? $file->fields : false;
      }

      public function getField($fieldName, $model)
      {
            if(!isset($this->items[$model]->$fieldName)) return false;
            return $this->items[$model]->$fieldName;
      }

}
