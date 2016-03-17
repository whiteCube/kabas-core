<?php

namespace Kabas\Config\Pages;

use \Kabas\Utils\File;
use \Kabas\App;

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

      /**
       * Recursively go through the files array to add
       * fields to the corresponding item
       * @return void
       */
      public function loopAndAddFields($files)
      {
            foreach($files as $file) {
                  if(is_array($file)) {
                        $this->loopAndAddFields($file);
                  } else {
                        $this->items[$file->id]->fields = $file->fields;
                  }
            }
      }

      /**
       * Loads the fields object for each page from the theme
       * @return void
       */
      public function loadFields()
      {
            $app = App::getInstance();
            $path = 'themes' . DIRECTORY_SEPARATOR . $app->config->settings->site->theme . DIRECTORY_SEPARATOR . 'pages';
            $files = File::loadJsonFromDir($path);
            $this->loopAndAddFields($files);
      }

}
