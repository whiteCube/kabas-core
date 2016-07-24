<?php

namespace Kabas\View;

use \RecursiveDirectoryIterator as RDI;
use \RecursiveIteratorIterator as RII;
use \Kabas\Utils\Assets;
use \Kabas\App;

class View
{
      private static $isFirst;

      public function __construct($view, $data, $directory)
      {
            if(self::isFirstView($view)) ob_start();

            extract((array) $data);
            include $this->getTemplateFile($view, $directory);

            if(self::isFirstView($view)){
                  $page = ob_get_contents();
                  ob_end_clean();
                  $page = Assets::load($page);
                  echo $page;
            }
      }

      /**
       * Check if view is the root one or not.
       * @param  string  $view
       * @return boolean
       */
      static function isFirstView($view)
      {
            if(!isset(self::$isFirst)) {
                  self::$isFirst = $view;
                  return true;
            }
            else if(self::$isFirst === $view) return true;
            return false;
      }

      /**
       * Includes the template
       * @param  string $view
       * @param  object $data
       * @return void
       */
      static function make($view, $data, $type = '')
      {
            App::getInstance()->make(self::class, [$view, $data, $type]);
      }

      /**
       * Looks for the template file within its direcotry.
       * @param  string $view
       * @return string
       */
      protected function getTemplateFile($view, $directory)
      {
            $directory = THEME_PATH . DS . 'views' . DS . $directory;
            $view = $this->checkViewExtension($view);

            return $directory . DS . $view;
      }

      /**
       * Checks if view string contains .php extension
       * and adds it if needed.
       * @param  string $view
       * @return string
       */
      protected function checkViewExtension($view)
      {
            if(strpos($view, '.php') !== false) return $view;
            return $view . '.php';
      }

      /**
       * Shows the default 404 page.
       * @return void
       */
      static function notFound()
      {
            if(ob_get_level()) ob_clean();
            require '404.php';
      }
}
