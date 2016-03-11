<?php

namespace Kabas\View;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;

class View
{
      /**
       * Includes the template
       *
       * @param  string $view
       * @param  object $data
       * @return void
       */
      static function make($view, $data)
      {
            include self::getTemplateFile($view);
      }

      /**
       * Find the base directory of the theme
       * and look for the template file within.
       *
       * @param  string $view
       * @return string
       */
      static function getTemplateFile($view)
      {
            global $app;

            $themeName = $app->config->settings->site->theme;
            $baseDir = __DIR__ . '/../../themes/' . $themeName;
            $view = self::checkViewExtension($view);

            return self::getTemplatePath($view, $baseDir);
      }

      /**
       * Finds the template file in it's directory
       *
       * @param  string $view       the name of the view file
       * @param  string $baseDir    the directory we want to search into
       * @return string
       */
      static function getTemplatePath($view, $baseDir) {
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
      static function checkViewExtension($view)
      {
            if(strpos($view, '.php') !== false) {
                  return $view;
            } else {
                  return $view . '.php';
            }
      }
}
