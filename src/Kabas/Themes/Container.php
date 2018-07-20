<?php

namespace Kabas\Themes;

class Container
{
    protected $available = [];

    protected $current;

    public function __construct()
    {
        $this->registerAvailable();
        $this->setCurrent(THEME);
    }

    /**
     * Sets given theme as used Theme
     * @param string $name
     * @return void
     */
    public function setCurrent($name)
    {
        if(!($theme = $this->get($name))){
            throw new \Kabas\Exceptions\NotFoundException($name, 'Theme', 500);
        }
        $this->current = $theme;
    }

    /**
     * Gets data for the current theme
     * @param string $key
     * @return mixed
     */
    public function getCurrent($key = null)
    {
        if(is_null($key)) return $this->current;
        return $this->current[$key] ?? null;
    }

    /**
     * Triggers recursive theme loading
     * @return void
     */
    public function loadCurrent()
    {
        if($this->current){
            $this->loadRecursivelyExcept($this->current['path'], ['public', 'structures', 'views', 'node_modules', 'Providers']);
        }
    }

    /**
     * Registers available themes in themes directory
     * @return void
     */
    protected function registerAvailable()
    {
        foreach (scandir(THEMES_PATH) as $item) {
            if(!in_array($item, ['.','..']) && is_dir($path = THEMES_PATH . DS . $item)){
                $this->available[$item] = $path;
            }
        }
    }

    /**
     * Finds & prepares theme in available themes
     * @param string $name
     * @return array
     */
    protected function get($name)
    {
        $theme = ['name' => $name];
        if(!isset($this->available[$name])) return false;
        if(!($theme['path'] = realpath($this->available[$name]))) return false;
        return $theme;
    }

    /**
     * Loads all PHP files for given directory, except excluded directories
     * @param string $directory
     * @param array $excluded
     * @return void
     */
    protected function loadRecursivelyExcept($directory, $excluded = [])
    {
        foreach (scandir($directory) as $item) {
            if(!in_array($item, ['.', '..'])){
                $path = $directory . DS . $item;
                if(is_dir($path) && !in_array($item, $excluded)){
                    $this->loadRecursivelyExcept($path);
                }
                elseif(is_file($path) && strtolower(pathinfo($path)['extension']) == 'php'){
                    require_once($path);
                }
            }
        }
    }

}
