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
            $path = 'content' . DS . $lang . DS . 'pages';
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
                        $this->items[$file->id] = App::getInstance()->make('\Kabas\Config\Pages\Item', [$file]);
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
                  . DS
                  . App::config()->settings->site->theme
                  . DS
                  . 'pages'
                  . DS
                  . $template;

            $file = File::loadJsonFromDir($path);
            $fields = isset($file[0]->fields) ? $file[0]->fields : new stdClass;
            $this->items[App::router()->getCurrentPageID()]->fields = $fields;
      }

}
