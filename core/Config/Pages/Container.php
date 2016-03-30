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
      private function instanciatePages()
      {
            $this->items = [];
            $lang = App::config()->settings->site->lang->active;
            $path = 'content' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'pages';
            $files = File::loadJsonFromDir($path);
            $this->loop($files);
      }

      /**
       * Recursively go through the files array to instanciate pages
       * @param  array $files
       * @return void
       */
      private function loop($files)
      {
            foreach($files as $file) {
                  if(is_array($file)) {
                        $this->loop($file);
                  } else {
                        $this->items[$file->id] = App::getInstance()->make('PageItem', [$file]);
                  }
            }
      }

      /**
       * Load the fields object from the theme.
       * @return void
       */
      public function loadCurrentPageFields()
      {
            $template = App::router()->getCurrentPageTemplate();
            $path =
                  'themes'
                  . DIRECTORY_SEPARATOR
                  . App::config()->settings->site->theme
                  . DIRECTORY_SEPARATOR
                  . 'pages'
                  . DIRECTORY_SEPARATOR
                  . $template;

            $file = File::loadJsonFromDir($path);
            $this->items[App::router()->getCurrentPageID()]->fields = $file[0]->fields;

      }

}
