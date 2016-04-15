<?php

namespace Kabas\Cmd;

use Kabas\Utils\File;
use Kabas\Utils\Text;

class Commander
{
      public function __construct($args)
      {
            $this->command = array_shift($args);
            $this->arguments = $args;
            $config = (require "config/site.php");
            $this->theme = $config['theme'];
            $this->setConstants();
            $this->executeCommand();
      }

      /**
       * Set constants to use throughout commands.
       * @return void
       */
      public function setConstants()
      {
            define('DS', DIRECTORY_SEPARATOR);
            define('TEMPLATES', 'core' . DS . 'Cmd' . DS . 'Templates' . DS);
            define('THEME_PATH', 'themes' . DS . $this->theme . DS);
            define('THEME_PAGES', THEME_PATH . 'pages');
            define('THEME_PARTS', THEME_PATH . 'parts');
            define('THEME_MENUS', THEME_PATH . 'menus');
      }

      /**
       * Point the command to the right method.
       * @return void
       */
      public function executeCommand()
      {
            if(!$this->command) return $this->help();

            switch($this->command){
                  case 'help': $this->help(); break;
                  case 'make:theme': $this->makeTheme(); break;
                  case 'make:page': $this->makePage(); break;
                  case 'make:part': $this->makePart(); break;
                  case 'make:menu': $this->makeMenu(); break;
                  default: echo "\n\033[31mKabas: Command '". $this->command ."' not found!\nUse \"php kabas help\" to view available commands.\n"; break;
            }
      }

      /**
       * Display help in the console.
       * @return void
       */
      public function help()
      {
            require  TEMPLATES . 'Help.php';
      }

      /**
       * Make the complete folder structure for a new theme.
       * @return void
       */
      public function makeTheme()
      {
            $theme = $this->arguments[0];
            if(!$theme) die("\n\033[31mKabas: Missing argument 1 for make:theme\nPlease specify the name of your theme (e.g. php kabas make:theme Papavo)\n");
            echo "Kabas: Creating directory structure for your theme...";
            $themePath = 'themes' . DS . $theme;
            $paths[] = $themePath;
            $paths[] = $themePath . DS . 'pages';
            $paths[] = $themePath . DS . 'parts';
            $paths[] = $themePath . DS . 'menus';
            $paths[] = $themePath . DS . 'assets';
            $paths[] = $themePath . DS . 'assets' . DS . 'css';
            $paths[] = $themePath . DS . 'assets' . DS . 'js';
            $paths[] = $themePath . DS . 'assets' . DS . 'img';
            $paths[] = $themePath . DS . 'models';
            foreach($paths as $path) {
                  mkdir($path);
            }
            echo "\n\033[32mDone!";
      }

      /**
       * Make the complete structure for a new page.
       * @return void
       */
      public function makePage()
      {
            $page = $this->arguments[0];
            if(!$part)  die("\n\033[31mKabas: Missing argument 1 for make:page\nPlease specify the name of your page (e.g. php kabas make:page contact)\n");
            $path = THEME_PAGES . DS . $page;
            echo "Kabas: Making page " . $page;
            mkdir($path);
            $this->makeController($path, $page, 'Pages', '\Kabas\Controller\BaseController', 'BaseController');
            $this->makeTemplate($path, $page);
            $this->makeConfig($path, $page);
            echo "\nWriting files to: " . $path;
            echo "\n\033[32mDone!";
      }

      /**
       * Make the complete structure for a new part.
       * @return void
       */
      public function makePart()
      {
            $part = $this->arguments[0];
            if(!$part)  die("\n\033[31mKabas: Missing argument 1 for make:part\nPlease specify the name of your part (e.g. php kabas make:part sidebar)\n");
            $path = THEME_PARTS . DS . $part;
            echo "Kabas: Making part " . $part;
            mkdir($path);
            $this->makeController($path, $part, 'Parts', '\Kabas\Controller\BaseController', 'BaseController');
            $this->makeTemplate($path, $part);
            $this->makeConfig($path, $part);
            echo "\nWriting files to: " . $path;
            echo "\n\033[32mDone!";
      }

      /**
       * Make the complete structure for a new menu.
       * @return void
       */
      public function makeMenu()
      {
            $menu = $this->arguments[0];
            if(!$part)  die("\n\033[31mKabas: Missing argument 1 for make:menu\nPlease specify the name of your menu (e.g. php kabas make:menu main)\n");
            $path = THEME_MENUS . DS . $menu;
            echo "Kabas: Making menu " . $menu;
            mkdir($path);
            $this->makeController($path, $menu, 'Menus', 'Kabas\Controller\MenuController', 'MenuController');
            $this->makeTemplate($path, $menu);
            echo "\nWriting files to: " . $path;
            echo "\n\033[32mDone!";
      }

      /**
       * Create a new controller file.
       * @param  string $path
       * @param  string $template
       * @param  string $type
       * @param  string $use
       * @param  strin $extends
       * @return void
       */
      public function makeController($path, $template, $type, $use, $extends)
      {
            $file = $path . DS . $template . '.class.php';
            $fileContent = File::read(TEMPLATES . 'Controller.php');
            $fileContent = str_replace('TOREPLACEtheme', $this->theme, $fileContent);
            $fileContent = str_replace('TOREPLACEtype', $type, $fileContent);
            $fileContent = str_replace('TOREPLACEuse', $use, $fileContent);
            $fileContent = str_replace('TOREPLACEextends', $extends, $fileContent);
            $fileContent = str_replace('TOREPLACEtemplate', Text::toNamespace($template), $fileContent);
            File::write($fileContent, $file);
      }

      /**
       * Create a new template file.
       * @param  string $path
       * @param  string $template
       * @return void
       */
      public function makeTemplate($path, $template)
      {
            $file = $path . DS . $template . '.php';
            File::write('', $file);
      }

      /**
       * Create a new configuration file.
       * @param  string $path
       * @param  string $template
       * @return void
       */
      public function makeConfig($path, $template)
      {
            $file = $path . DS . $template;
            File::writeJson(["fields" => new \stdClass], $file);
      }
}
