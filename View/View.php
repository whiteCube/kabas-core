<?php

namespace Kabas\View;

use \RecursiveDirectoryIterator as RDI;
use \RecursiveIteratorIterator as RII;
use \Kabas\Utils\Assets;
use Kabas\Utils\Url;
use \Kabas\App;

class View
{
      private static $isFirst;

      public function __construct($view, $data, $type)
      {
            if(self::isFirstView($view)) ob_start();

            extract((array) $data);
            include $this->getTemplateFile($view, $type);

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
       * Find the base directory of the theme
       * and look for the template file within.
       * @param  string $view
       * @return string
       */
      protected function getTemplateFile($view, $type)
      {
            $app = App::getInstance();

            $themeName = $app->config->settings->site->theme;
            $baseDir = THEME_PATH . DS . $type;
            $view = $this->checkViewExtension($view);

            return $this->getTemplatePath($view, $baseDir);
      }

      /**
       * Finds the template file in its directory
       * @param  string $view       the name of the view file
       * @param  string $baseDir    the directory we want to search into
       * @return string
       */
      protected function getTemplatePath($view, $baseDir) {
            $oDirectory = new RDI($baseDir);
            $oIterator = new RII($oDirectory);
            foreach($oIterator as $oFile) {
                  if ($oFile->getFilename() == $view) {
                        return $oFile->getPath() . DS . $oFile->getFilename();
                  }
            }
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
            require '404.php';
      }
}
