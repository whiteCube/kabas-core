<?php

namespace Kabas\Config\Menus;

use \Kabas\Utils\File;
use \Kabas\App;

class Container
{
      public function __construct()
      {
            $this->instanciateMenus();
      }

      /**
       * Load json files and instanciate parts
       * @return void
       */
      public function instanciateMenus()
      {
            $this->items = [];
            $app = App::getInstance();
            $lang = $app->config->settings->site->lang;
            $path = 'content' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . 'menus';
            $files = File::loadJsonFromDir($path);
            $this->loop($files);
      }

      /**
       * Recursively go through the files array to instanciate menus
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
      public function hasMenu($menuID)
      {
            if(array_key_exists($menuID, $this->items)) return true;
            return false;
      }

      /**
       * Get part if it exists
       * @param  string $partID
       * @return object
       */
      public function getMenu($menuID)
      {
            if($this->hasMenu($menuID)) return $this->items[$menuID];
            else return 'error, menu does not exist';
      }

}
