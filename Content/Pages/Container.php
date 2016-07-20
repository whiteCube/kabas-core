<?php

namespace Kabas\Content\Pages;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{
      protected function getPath()
      {
            return parent::getPath() . DS . 'pages';
      }

      protected function makeItem($file)
      {
            return App::getInstance()->make('\Kabas\Content\Pages\Item', [$file]);
      }

      /**
       * Load the fields object from the theme.
       * @return void
       */
      public function loadCurrentPageFields()
      {
            $template = $this->getPage(App::router()->getCurrent()->page)->template;
            $path = THEME_PATH . DS . 'structures' . DS . 'templates' . DS . $template;
            $file = File::loadJsonFromDir($path);
            $fields = isset($file[0]->fields) ? $file[0]->fields : new \stdClass;
            $this->items[App::router()->getCurrent()->page]->fields = $fields;
      }

      public function getPage($id)
      {
            if(array_key_exists($id, $this->items)) return $this->items[$id];
      }

}
