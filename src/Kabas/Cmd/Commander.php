<?php

namespace Kabas\Cmd;

use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Config\Settings;
use Kabas\Config\LanguageRepository;

class Commander
{
    protected $command;
    protected $arguments;
    protected $config;
    protected $theme;
    protected $lang;

    public function __construct($projectDir, $args)
    {
        $this->setConstants($projectDir);
        $this->command = array_shift($args);
        $this->arguments = $args;
        $this->settings = new Settings();
        $this->languages = new LanguageRepository($this->settings->pluck('lang.available'), $this->settings->get('lang.default'));
        $this->setThemeConstants();
        $this->executeCommand();
    }

    /**
     * Set constants to use throughout commands.
     * @param string $projectDir
     * @return void
     */
    protected function setConstants($projectDir)
    {
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath($projectDir));
        if(!defined('TEMPLATES_PATH')) define('TEMPLATES_PATH', __DIR__ . DS . 'Templates' . DS);
        if(!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . DS . 'config');
        if(!defined('THEMES_PATH')) define('THEMES_PATH', ROOT_PATH . DS . 'themes');
    }

    /**
     * Set constants for theme paths
     * @return void
     */
    protected function setThemeConstants()
    {
        if(!defined('THEME_STRUCTURES')) define('THEME_STRUCTURES', THEME_PATH . DS . 'structures');
        if(!defined('THEME_VIEWS')) define('THEME_VIEWS', THEME_PATH . DS . 'views');
        if(!defined('THEME_CONTROLLERS')) define('THEME_CONTROLLERS', THEME_PATH . DS . 'controllers');
        if(!defined('THEME_MODELS')) define('THEME_MODELS', THEME_PATH . DS . 'models');
    }

    /**
     * Point the command to the right method.
     * @return void
     */
    protected function executeCommand()
    {
        if(!$this->command) return $this->help();
        switch($this->command){
            case 'help': $this->help(); break;
            case 'make:theme': $this->makeTheme(); break;
            case 'make:template': $this->makeThemeContentFile('template', 'contact'); break;
            case 'make:partial': $this->makeThemeContentFile('partial', 'sidebar'); break;
            case 'make:menu': $this->makeThemeContentFile('menu', 'main'); break;
            case 'make:model': $this->makeModel(); break;
            case 'content:page': $this->makeContent('page','templates'); break;
            case 'content:partial': $this->makeContent('partial','partials'); break;
            case 'content:menu': $this->makeContent('menu','menus'); break;
            case 'content:object': $this->makeContent('object','models'); break;
            default: echo "\n\033[31mKabas: Command '". $this->command ."' not found!\nUse \"php kabas help\" to view available commands.\n"; break;
        }
    }

    /**
     * Display help in the console.
     * @return void
     */
    protected function help()
    {
        require TEMPLATES_PATH . 'Help.php';
    }

    /**
     * Make the complete folder structure for a new theme.
     * @return void
     */
    protected function makeTheme()
    {
        $theme = $this->arguments[0] ?? null;
        if(!$theme) throw new \Exception("Missing argument 1 for 'make:theme'. Please specify the name of your theme (e.g. php kabas make:theme MyTheme)");
        echo 'Kabas: Creating directory structure for "' . $theme . '"...';
        $themePath = THEMES_PATH . DS . $theme;
        $paths = [];
        $paths[] = $themePath;
        $paths[] = $themePath . DS . 'models';
        $paths[] = $themePath . DS . 'lang';
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
        foreach($paths as $path) {
            mkdir($path);
        }
        echo "\nDone!";
    }

    /**
     * Generates the files for a new theme content-type.
     * @param  string $type
     * @param  string $example
     * @return void
     */
    protected function makeThemeContentFile($type, $example)
    {
        $name = $this->arguments[0] ?? null;
        if(!$name) throw new \Exception("Missing argument 1 for '" . $this->command . "'. Please specify the name of your " . $type . " (e.g. 'php kabas " . $this->command . " " . $example . "')");
        echo 'Kabas: Making ' . $type . ' "' . $name . '"';
        $this->makeControllerFile($name, $type);
        $this->makeViewFile($name, $type);
        $this->makeStructureFile($name, $type);
        echo "\nDone!";
    }

    /**
     * Make the complete structure for a new model.
     * @return void
     */
    protected function makeModel()
    {
        //  TODO : this needs to be refactored for new models.
        $model = $this->arguments[0];
        $driver = $this->arguments[1];
        if(!$model) die("\n\033[31mKabas: Missing argument 1 for make:model\nPlease specify the name of your model (e.g. php kabas make:model news eloquent)\n");
        if(!$driver) die("\n\033[31mKabas: Missing argument 2 for make:model\nPlease specify the driver of your model (e.g. php kabas make:model news eloquent)\n");
        if($driver !== 'eloquent' && $driver !== 'json') die("\n\033[31mKabas: Please specify a valid driver to use with your model. ('eloquent' or 'json')\n");
        echo "Kabas: Making model " . $model;
        $this->makeModelFile($model, $driver);
        $this->makeStructureFile($model, 'model');
        echo "\n\033[32mDone!";
    }

    /**
     * Create a new model file.
     * @param  string $name
     * @param  string $driver
     * @return void
     */
    protected function makeModelFile($name, $driver)
    {
        $file = $this->dir(THEME_MODELS) . DS . ucfirst($name) . '.php';
        $fileContent = File::read(TEMPLATES_PATH . 'Model.php');
        $fileContent = str_replace('##THEME##', $this->theme, $fileContent);
        $fileContent = str_replace('##NAME##', Text::toNamespace($name), $fileContent);
        $fileContent = str_replace('##DRIVER##', $driver, $fileContent);
        $fileContent = str_replace('##TABLE##', $name, $fileContent);
        File::write($fileContent, $file);
    }

    /**
     * Create a new controller file.
     * @param  string $name
     * @param  string $type
     * @return void
     */
    protected function makeControllerFile($name, $type)
    {
        $file = $this->dir(THEME_CONTROLLERS . DS . $type . 's') . DS . ucfirst($name) . '.php';
        $fileContent = File::read(TEMPLATES_PATH . DS . 'Controller.php');
        $fileContent = str_replace('##THEME##', THEME, $fileContent);
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
    protected function makeViewFile($name, $type)
    {
        $file = $this->dir(THEME_VIEWS . DS . $type . 's') . DS . $name . '.php';
        File::write('', $file);
    }

    /**
     * Create a new structure file.
     * @param  string $name
     * @param  string $type
     * @return void
     */
    protected function makeStructureFile($name, $type)
    {
        $file = $this->dir(THEME_STRUCTURES . DS . $type . 's') . DS . $name;
        $structure = ['fields' => new \stdClass];
        if($this->command == 'make:menu'){
            $structure = [
                'item' => ['label' => ['type' => 'text', 'label' => 'Name'], 'target' => ['type' => 'url', 'label' => 'Target page']],
                'fields' => new \stdClass
            ];
        }
        File::writeJson($structure, $file);
    }

    /**
     * Make a content file for a content-type
     * @param  string $type
     * @param  string $structure
     * @return void
     */
    protected function makeContent($type, $structure)
    {
        $name = array_shift($this->arguments);
        echo 'Kabas: making content for ' . $type . ' "' . $name . '"';
        foreach($this->checkLangs($this->arguments) as $lang) {
            $fields = $this->fetchFields($structure, $name);
            if($type == 'object') $this->generateObjectFile($name, $lang, $fields);
            else $this->generateContentFile($name, $type . 's', $lang, $fields);
        }
        echo "\nDone!";
    }

    /**
     * Make a content file.
     * @param  string $name
     * @param  string $type
     * @param  string $lang
     * @param  object $fields
     * @return void
     */
    protected function generateContentFile($name, $type, $lang, $fields = null)
    {
        $file = $this->dir('content' . DS . $lang . DS . $type) . DS . $name;
        if(!$fields) $fields = new \stdClass;
        $content = [];
        if($type == 'pages'){
            $content['route'] = '';
            $content['title'] = '';
        }
        $content['id'] = $name;
        $content['template'] = $name;
        $content['data'] = $fields;
        if($type == 'menus'){
            $content['items'] = [];
        }
        echo "\nWriting file to " . $file;
        File::writeJson($content, $file);
    }

    /**
     * Creates content file for a model
     * @param string $model 
     * @param string $lang 
     * @param array $fields 
     * @return void
     */
    protected function generateObjectFile($model, $lang, $fields)
    {
        $path = $this->dir('content' . DS . $lang . DS . 'objects' . DS . $model);
        $files = scandir($path);
        $id = intval(pathinfo($files[count($files) - 1], PATHINFO_FILENAME));
        $file = $path . DS . (++$id);
        File::writeJson($fields, $file);
    }

    /**
     * Checks that specified languages exist in application.
     * @param  array $languages
     * @return array
     */
    protected function checkLangs($languages)
    {
        if(!$languages) return array_map(function($item){ return $item->original;}, $this->languages->getAll());
        foreach($languages as $locale) {
            if($this->languages->has($locale)) continue;
            throw new \Exception('Locale "' . $locale . '" is not defined in the lang.php configuration file.'); // @codeCoverageIgnore
        }
        return $languages;
    }

    /**
     * Get the field descriptions of the specified content-type.
     * @param  string $type
     * @param  string $name
     * @return array
     */
    protected function fetchFields($type, $name)
    {
        $path = THEME_STRUCTURES . DS . $type . DS . $name . '.json';
        $config = File::loadJson($path);
        $fields = [];
        if(is_object($config) && isset($config->fields)){
            foreach($config->fields as $key => $field){
                $fields[$key] = $this->formatFieldContent($key, $field);
            }
        }
        return $fields;
    }

    /**
     * Format the field so it can be filled with content later.
     * @param  string $key
     * @param  object $field
     * @return mixed
     */
    protected function formatFieldContent($key, $field)
    {
        switch($field->type ?? 'text'){
            case 'image': return $this->getImageFieldContent($key, $field); break;
            case 'number': return $this->getNumberFieldContent($key, $field); break;
            case 'checkbox':
            case 'radio':
            case 'select': return $this->getSelectableFieldContent($key, $field); break;
        }
        if(!is_null($field->default ?? null)) return $field->default;
        return '';
    }

    /**
     * Generates a pre-filled object for an image field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getImageFieldContent($key, $field)
    {
        $image = new \stdClass();
        $image->path = '';
        $image->alt = '';
        if(!isset($field->default)) return $image;
        if(!is_object($field->default)){
            throw new \Exception('default value for image field "' . $key . '" is invalid.'); // @codeCoverageIgnore
        }
        $image->path = is_string($field->default->path ?? null) ? $field->default->path : '';
        $image->alt = is_string($field->default->alt ?? null) ? $field->default->alt : '';
        return $image;
    }

    /**
     * Generates a pre-filled object for a number field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getNumberFieldContent($key, $field)
    {
        if(is_null($field->default ?? null)) return 0;
        if(is_numeric($field->default)) return $field->default;
        throw new \Exception('default value for number field "' . $key . '" is invalid.'); // @codeCoverageIgnore
    }

    /**
     * Generates a pre-filled object for a selectable field
     * @param string $key 
     * @param object $field 
     * @return object
     */
    protected function getSelectableFieldContent($key, $field)
    {
        if(is_null($field->default ?? null)) return [];
        if(is_array($field->default)) return $field->default;
        throw new \Exception('default value for ' . $field->type . ' field "' . $key . '" is invalid.'); // @codeCoverageIgnore
    }

    /**
     * Gets (or creates) the path to a directory
     * @param string $path 
     * @return string
     */
    protected function dir($path){
        if(!realpath($path)) mkdir($path, 0755, true);
        return realpath($path);
    }

}
