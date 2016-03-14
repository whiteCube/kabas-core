<?php

namespace Kabas\View;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use Kabas\Utils\Url;
use \Kabas\Kabas;

class View
{

      public function __construct($view, $data)
      {
            extract((array) $data);
            include $this->getTemplateFile($view);
      }

      /**
       * Includes the template
       *
       * @param  string $view
       * @param  object $data
       * @return void
       */
      static function make($view, $data)
      {
            new self($view, $data);
      }

      /**
       * Find the base directory of the theme
       * and look for the template file within.
       *
       * @param  string $view
       * @return string
       */
      protected function getTemplateFile($view)
      {
            $app = Kabas::getInstance();

            $themeName = $app->config->settings->site->theme;
            $baseDir = __DIR__ . '/../../themes/' . $themeName;
            $view = $this->checkViewExtension($view);

            return $this->getTemplatePath($view, $baseDir);
      }

      /**
       * Finds the template file in its directory
       *
       * @param  string $view       the name of the view file
       * @param  string $baseDir    the directory we want to search into
       * @return string
       */
      protected function getTemplatePath($view, $baseDir) {
            $oDirectory = new RecursiveDirectoryIterator($baseDir);
            $oIterator = new RecursiveIteratorIterator($oDirectory);
            foreach($oIterator as $oFile) {
                  if ($oFile->getFilename() == $view) {
                        return $oFile->getPath() . DIRECTORY_SEPARATOR . $oFile->getFilename();
                  }
            }
      }

      /**
       * Checks if view string contains .php extension
       * and adds it if needed.
       *
       * @param  string $view
       * @return string
       */
      protected function checkViewExtension($view)
      {
            if(strpos($view, '.php') !== false) {
                  return $view;
            } else {
                  return $view . '.php';
            }
      }
}
