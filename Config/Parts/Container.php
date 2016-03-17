<?php

namespace Kabas\Config\Parts;

use \Kabas\Utils\File;
use Kabas\App;

class Container
{
      public function __construct()
      {
            $this->instanciateParts();
            $this->instanciateHeader();
            $this->instanciateFooter();
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
       * Instanciate the header part
       * @return void
       */
      public function instanciateHeader()
      {
            $file = File::loadJson('content/header.json');
            if(isset($file)){
                  $file->template = "header";
                  $this->items['header'] = new Item($file);
            }
      }

      /**
       * Instanciate the footer part
       * @return void
       */
      public function instanciateFooter()
      {
            $file = File::loadJson('content/footer.json');
            if(isset($file)) {
                  $file->template = "footer";
                  $this->items['footer'] = new Item($file);
            }
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
       * Loads the fields object for each part from the theme
       * @return void
       */
      public function loadFields()
      {
            $app = App::getInstance();
            $path = 'themes' . DIRECTORY_SEPARATOR . $app->config->settings->site->theme . DIRECTORY_SEPARATOR . 'parts';
            $files = File::loadJsonFromDir($path);
            $this->loopAndAddFields($files);
      }

}
