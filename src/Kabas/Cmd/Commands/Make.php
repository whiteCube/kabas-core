<?php 

namespace Kabas\Cmd\Commands;

use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Exceptions\InvalidDriverException;
use Kabas\Exceptions\ArgumentMissingException;

class Make extends Command
{

    public function theme($theme = null)
    {
        \Kabas\Utils\Debug::backtrace();
        die();
        if(!$theme) throw new ArgumentMissingException('make:theme', 'Missing argument 1. Please specify the name of your theme (e.g. php kabas make:theme MyTheme)');
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

    public function template($template = null)
    {
        return $this->makeThemeContentFile('template', $template);
    }

    public function partial($partial = null)
    {
        return $this->makeThemeContentFile('partial', $partial);
    }

    public function menu($menu = null)
    {
        return $this->makeThemeContentFile('menu', $menu);
    }

    public function model($model, $driver = null)
    {
        $driver = $driver ?? $this->getDefaultDriver();
        if(!$model) throw new ArgumentMissingException('make:model', 'Missing argument 1. Please specify the name of your model (e.g. php kabas make:model news eloquent)');
        if(!$driver) throw new ArgumentMissingException('make:model', 'Missing argument 2. Please specify the driver of your model (e.g. php kabas make:model news eloquent)');
        if($driver !== 'eloquent' && $driver !== 'json') throw new InvalidDriverException($driver);
        echo 'Kabas: Making model ' . $model;
        $this->makeModelFile($model, $driver);
        $this->makeStructureFile($model, 'model');
        echo "\n\033[32mDone!";
    }

    /**
     * Generates the files for a new theme content-type.
     * @param  string $type
     * @param  string $example
     * @return void
     */
    protected function makeThemeContentFile($type, $name)
    {
        if(!$name) throw new ArgumentMissingException('make:' . $type, 'Missing argument 1. Please specify the name of your ' . $type . ' (e.g. php kabas make:' . $type . ' foo)');
        echo 'Kabas: Making ' . $type . ' "' . $name . '"';
        $this->makeControllerFile($name, $type);
        $this->makeViewFile($name, $type);
        $this->makeStructureFile($name, $type);
        echo "\nDone!";
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
        if($type == 'menu'){
            $structure = [
                'item' => ['label' => ['type' => 'text', 'label' => 'Name'], 'target' => ['type' => 'url', 'label' => 'Target page']],
                'fields' => new \stdClass
            ];
        }
        File::writeJson($structure, $file);
    }

    /**
     * Create a new controller file.
     * @param  string $name
     * @param  string $type
     * @return void
     */
    protected function makeControllerFile($name, $type)
    {
        $template = 'Controller.php'; 
        $file = $this->dir(THEME_CONTROLLERS . DS . $type . 's') . DS . ucfirst($name) . '.php';
        $this->scaffold($template, $file, [
            '##THEME##' => THEME,
            '##TYPE##' => ucfirst($type . 's'),
            '##TYPECONTROLLER##' => Text::toNamespace($type . 'Controller'),
            '##NAME##' => Text::toNamespace($name)
        ]);
    }

    /**
     * Gets the template, fills it with values, then writes it to disk
     * @param string $templatePath 
     * @param string $file 
     * @param array $data 
     * @return void
     */
    protected function scaffold($templateName, $file, $data)
    {
        $template = File::read(TEMPLATES_PATH . $templateName);
        $template = $this->insertData($template, $data);
        File::write($template, $file);
    }

    /**
     * Fills the values inside a template
     * @param string $template 
     * @param array $data 
     * @return string
     */
    protected function insertData($template, $data)
    {
        foreach($data as $key => $value) {
            $template = str_replace($key, $value, $template);
        }
        return $template;
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
     * Create a new model file.
     * @param  string $name
     * @param  string $driver
     * @return void
     */
    protected function makeModelFile($name, $driver)
    {
        $template = 'Model.php';
        $file = $this->dir(THEME_MODELS) . DS . ucfirst($name) . '.php';
        $this->scaffold($template, $file, [
            '##THEME##' => THEME,
            '##NAME##' => Text::toNamespace($name),
            '##DRIVER##' => Text::toNamespace($driver),
            '##TABLE##' => $name
        ]);
    }

    /**
     * Gets the default driver defined in config
     * @return string
     */
    protected function getDefaultDriver()
    {
        $app = include(CONFIG_PATH . DS . 'app.php');
        return isset($app['driver']) ? $app['driver'] : null;
    }

}