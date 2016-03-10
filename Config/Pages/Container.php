<?php

namespace Kabas\Config\Pages;

use \Kabas\Utils\File;

class Container
{
      public function __construct()
      {
            $this->instanciatePages();
      }

      /**
       * Load json files and instanciate pages
       * @return void
       */
      public function instanciatePages()
      {
            $this->items = [];
            $files = File::loadJsonFromDir('content/pages');
            $this->loop($files);
      }

      /**
       * Recursively go through the files array to instanciate pages
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

}
