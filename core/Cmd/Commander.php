<?php

namespace Kabas\Cmd;

use Kabas\Utils\File;
use Kabas\Utils\Text;

class Commander
{
      protected $command;
      protected $arguments;
      protected $config;
      protected $theme;

      public function __construct($args)
      {
            $this->command = array_shift($args);
            $this->arguments = $args;
            $this->config = (require "config/site.php");
            $this->theme = $this->config['theme'];
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
            define('THEME_STRUCTURES', THEME_PATH . 'structures');
            define('THEME_VIEWS', THEME_PATH . 'views');
            define('THEME_CONTROLLERS', THEME_PATH . 'controllers');
            define('THEME_MODELS', THEME_PATH . 'models');
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
                  case 'make:template': $this->makeThemeContentFile('template', 'contact'); break;
                  case 'make:partial': $this->makeThemeContentFile('partial', 'sidebar'); break;
                  case 'make:menu': $this->makeThemeContentFile('menu', 'main'); break;
                  case 'make:model': $this->makeModel(); break;
                  case 'content:page': $this->makePageContent(); break;
                  case 'content:part': $this->makePartContent(); break;
                  case 'content:menu': $this->makeMenuContent(); break;
                  case 'content:model': $this->makeModelContent(); break;
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
            $paths = [];
            $paths[] = $themePath;
            $paths[] = $themePath . DS . 'models';
            $paths[] = $themePath . DS . 'controllers';
            $paths[] = $themePath . DS . 'controllers' . DS . 'menus';
            $paths[] = $themePath . DS . 'controllers' . DS . 'partials';
            $paths[] = $themePath . DS . 'controllers' . DS . 'templates';
            $paths[] = $themePath . DS . 'views';
            $paths[] = $themePath . DS . 'views' . DS . 'menus';
            $paths[] = $themePath . DS . 'views' . DS . 'partials';
            $paths[] = $themePath . DS . 'views' . DS . 'templates';
            $paths[] = $themePath . DS . 'structures';
            $paths[] = $themePath . DS . 'structures' . DS . 'menus';
            $paths[] = $themePath . DS . 'structures' . DS . 'models';
            $paths[] = $themePath . DS . 'structures' . DS . 'partials';
            $paths[] = $themePath . DS . 'structures' . DS . 'templates';
            $paths[] = $themePath . DS . 'public';
            foreach($paths as $path) {
                  mkdir($path);
            }
            echo "\n\033[32mDone!";
      }

      /**
       * Generates the files for a new theme content-type.
       * @param  string $type
       * @param  string $example
       * @return void
       */
      public function makeThemeContentFile($type, $example)
      {
            $name = $this->arguments[0];
            if(!$name) die("\n\033[31mKabas: Missing argument 1 for " . $this->command . "\nPlease specify the name of your " . $type . " (e.g. php kabas " . $this->command . " " . $example . ")\n");
            echo "Kabas: Making " . $type . " " . $name;
            $this->makeControllerFile($name, $type);
            $this->makeViewFile($name, $type);
            $this->makeStructureFile($name, $type);
            echo "\n\033[32mDone!";
      }

      /**
       * Make the complete structure for a new model.
       * @return void
       */
      public function makeModel()
      {
            $model = $this->arguments[0];
            $driver = $this->arguments[1];
            if(!$model) die("\n\033[31mKabas: Missing argument 1 for make:model\nPlease specify the name of your model (e.g. php kabas make:model news eloquent)\n");
            if(!$driver) die("\n\033[31mKabas: Missing argument 2 for make:model\nPlease specify the driver of your model (e.g. php kabas make:model news eloquent)\n");
            if($driver !== 'eloquent' && $driver !== 'json') die("\n\033[31mKabas: Please specify a valid driver to use with your model. ('eloquent' or 'json')\n");
            $path = THEME_MODELS . DS . $model;
            echo "Kabas: Making model " . $model;
            mkdir($path);
            $this->makeModelFile($model, $driver, $path);
            $this->makeStructureFile($path, $model);
            echo "\nWriting files to: " . $path;
            echo "\n\033[32mDone!";
      }

      /**
       * Create a new model file.
       * @param  string $model
       * @param  string $driver
       * @param  string $path
       * @return void
       */
      public function makeModelFile($model, $driver, $path)
      {
            if(!realpath($path)) mkdir($path);
            $file = $path . DS . $model . '.class.php';
            $fileContent = File::read(TEMPLATES . 'Model.php');
            $fileContent = str_replace('TOREPLACEtheme', $this->theme, $fileContent);
            $fileContent = str_replace('TOREPLACEdriver', $driver, $fileContent);
            $fileContent = str_replace('TOREPLACEtable', $model, $fileContent);
            $fileContent = str_replace('TOREPLACEmodel', Text::toNamespace($model), $fileContent);
            File::write($fileContent, $file);
      }

      /**
       * Create a new controller file.
       * @param  string $name
       * @param  string $type
       * @return void
       */
      public function makeControllerFile($name, $type)
      {
            $file = $this->dir(THEME_CONTROLLERS . DS . $type) . DS . ucfirst($name) . '.php';
            $fileContent = File::read(TEMPLATES . 'Controller.php');
            $fileContent = str_replace('##THEME##', $this->theme, $fileContent);
            $fileContent = str_replace('##TYPE##', ucfirst($type . 's'), $fileContent);
            $fileContent = str_replace('##TYPECONTROLLER##', Text::toNamespace($type . 'Controller'), $fileContent);
            $fileContent = str_replace('##NAME##', Text::toNamespace($name), $fileContent);
            File::write($fileContent, $file);
      }

      /**
       * Create a new view file.
       * @param  string $name
       * @param  string $type
       * @return void
       */
      public function makeViewFile($name, $type)
      {
            $file = $this->dir(THEME_VIEWS . DS . $type) . DS . $name . '.php';
            File::write('', $file);
      }

      /**
       * Create a new structure file.
       * @param  string $name
       * @param  string $type
       * @return void
       */
      public function makeStructureFile($name, $type)
      {
            $file = $this->dir(THEME_STRUCTURES . DS . $type) . DS . $name;
            $structure = ["fields" => new \stdClass];
            if($this->command == 'make:menu'){
                  $structure = [
                        "item" => ["label" => ["type" => "text", "label" => "Name"], "target" => ["type" => "url", "label" => "Target page"]],
                        "fields" => new \stdClass
                  ];
            }
            File::writeJson($structure, $file);
      }

      /**
       * Make a content file.
       * @param  string $path
       * @param  string $template
       * @param  string $type
       * @param  object $fields
       * @return void
       */
      public function makeContentFile($path, $template, $type, $fields = null)
      {
            $file = $path . DS . $template;
            if(!$fields) $fields = new \stdClass;

            $fileContents = [];
            if($type === 'pages') $fileContents['route'] = '';
            $fileContents['id'] = $template;
            $fileContents['template'] = $template;
            if($type === 'pages') $fileContents['title'] = '';
            if($type !== 'menus') $fileContents['data'] = $fields;
            if($type === 'menus') $fileContents['links'] = new \stdClass;
            $fileContents['options'] = new \stdClass;

            File::writeJson($fileContents, $file);
      }

      public function makeObjectFile($path, $model, $fields)
      {
            $path = $path . DS . $model;
            $files = scandir($path);
            $lastIndex = count($files) - 1;
            $lastId = intval(pathinfo($files[$lastIndex], PATHINFO_FILENAME));
            $id = ++$lastId;
            $file = $path . DS . $id;
            File::writeJson($fields, $file);
      }

      /**
       * Make a content file for a page
       * @return void
       */
      public function makePageContent()
      {
            $page = array_shift($this->arguments);
            $langs = $this->arguments;
            $langs = $this->checkLangs($langs);
            echo 'Kabas: making content for page ' . $page;
            foreach($langs as $lang) {
                  $path = 'content' . DS . $lang . DS . 'pages';
                  $fields = $this->fetchFields('pages', $page);
                  $this->makeContentFile($path, $page, 'pages', $fields);
                  echo "\nWriting files to: " . $path;
            }
            echo "\n\033[32mDone!";
      }

      /**
       * Make a content file for a part.
       * @return void
       */
      public function makePartContent()
      {
            $part = array_shift($this->arguments);
            $langs = $this->arguments;
            $langs = $this->checkLangs($langs);
            echo 'Kabas: making content for part ' . $part;
            foreach($langs as $lang) {
                  $path = 'content' . DS . $lang . DS . 'parts';
                  $fields = $this->fetchFields('parts', $part);
                  $this->makeContentFile($path, $part, 'parts', $fields);
                  echo "\nWriting files to: " . $path;
            }
            echo "\n\033[32mDone!";
      }

      /**
       * Make a content file for a menu.
       * @return void
       */
      public function makeMenuContent()
      {
            $menu = array_shift($this->arguments);
            $langs = $this->arguments;
            $langs = $this->checkLangs($langs);
            echo 'Kabas: making content for menu ' . $menu;
            foreach($langs as $lang) {
                  $path = 'content' . DS . $lang . DS . 'menus';
                  $this->makeContentFile($path, $menu, 'menus');
                  echo "\nWriting files to: " . $path;
            }
            echo "\n\033[32mDone!";
      }

      public function makeModelContent()
      {
            $model = array_shift($this->arguments);
            $langs = $this->arguments;
            $langs = $this->checkLangs($langs);
            echo 'Kabas: making content for model ' . $model;
            foreach($langs as $lang) {
                  $path = 'content' . DS . $lang . DS . 'objects';
                  mkdir($path . DS . $model, 0777, true);
                  $fields = $this->fetchFields('models', $model);
                  $this->makeObjectFile($path, $model, $fields);
                  echo "\nWriting files to: " . $path;
            }
            echo "\n\033[32mDone!";
      }

      /**
       * Checks that specified langs exist in application.
       * @param  array $langs
       * @return array
       */
      public function checkLangs($langs)
      {
            $availableLangs = $this->config['lang']['available'];
            if(!$langs) return $availableLangs;
            else {
                  foreach($langs as $lang) {
                        if(!in_array($lang, $availableLangs)){
                              die("\n\033[31mKabas: Lang $lang does not currently exist. Please create the appropriate content subfolder.\n");
                        }
                  }
                  return $langs;
            }
      }

      /**
       * Get the field descriptions of the specified template.
       * @param  string $type
       * @param  string $template
       * @return array
       */
      public function fetchFields($type, $template)
      {
            $path = THEME_PATH . DS . $type . DS . $template . DS . $template . '.json';
            $config = File::loadJson($path);
            foreach($config->fields as $key => $field){
                  $fields[$key] = $this->formatFieldContent($field);
            }
            return $fields;
      }

      /**
       * Format the field so it can be filled with content later.
       * @param  object $field
       * @return mixed
       */
      public function formatFieldContent($field)
      {
            switch($field->type){
                  case 'image':
                        $field = new \stdClass;
                        $field->src = '';
                        $field->alt = '';
                        break;
                  case 'url':
                        $field = new \stdClass;
                        $field->href = '';
                        $field->label = '';
                        $field->title = '';
                        break;
                  case 'checkbox':
                  case 'radio':
                  case 'select':
                        $field = [];
                        $field[0] = new \stdClass;
                        $field[0]->id = '';
                        $field[0]->label = '';
                        $field[0]->selected = false;
                        break;
                  case 'number':
                        $field = 0;
                        break;
                  default: $field = '';
            }

            return $field;
      }

      protected function dir($path){
            if(!realpath($path)) mkdir($path, 0755, true);
            return realpath($path);
      }

}
