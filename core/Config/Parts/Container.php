<?php

namespace Kabas\Config\Parts;

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
      public function loadPart($partID)
      {
            $lang = App::config()->settings->site->lang->active;
            $path = 'content' . DS . $lang . DS . 'parts' . DS . $partID . '.json';
            $file = File::loadJson($path);
            $this->items[$partID] = App::getInstance()->make('PartItem', [$file]);
            $this->loadPartFields($partID);
      }

      /**
       * Read the fields for the specified part and load them into memory.
       * @param  string $partID
       * @return void
       */
      public function loadPartFields($partID)
      {
            $path = THEME_PATH . DS . 'parts' . DS . $partID;
            $file = File::loadJsonFromDir($path);
            $this->items[$partID]->fields = $file[0]->fields;
      }

}
