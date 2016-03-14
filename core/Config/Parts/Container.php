<?php

namespace Kabas\Config\Parts;

use \Kabas\Utils\File;

class Container
{
      public function __construct()
      {
            $this->instanciateParts();
      }

      /**
       * Load json files and instanciate parts
       * @return void
       */
      public function instanciateParts()
      {
            $this->items = [];
            $files = File::loadJsonFromDir('content/parts');
            $this->loop($files);
      }

      /**
       * Recursively go through the files array to instanciate parts
       * @param  array $files
       * @return void
       */
      public function loop($files)
      {
            foreach($files as $file) {
                  if(is_array($file)) {
                        $this->loop($file);
                  } else {
                        $this->items[$file->id] = new Item($file);
                  }
            }
      }

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

}
