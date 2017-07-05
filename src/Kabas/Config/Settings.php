<?php

namespace Kabas\Config;

use Kabas\App;

class Settings
{
    protected $tree = [];

    public function __construct()
    {
        $this->registerConfigurationDirectory(CONFIG_PATH);
        $this->registerConfigurationConstants();
    }

    /**
     * Sets data in settings tree using the dots notation
     * @param string $tree
     * @param array $data
     * @return multiple
     */
    public function set($tree, $data = [])
    {
        $path = explode('.', $tree);
        return $this->registerInTree($this->tree, $path, $data);
    }

    /**
     * Finds data in settings tree using the dots notation
     * @param string $tree
     * @return multiple
     */
    public function get($tree)
    {
        $path = explode('.', $tree);
        return $this->findInTree($this->tree, $path);
    }

    /**
     * Removes data using the dots notation
     * @param string $tree
     * @return void
     */
    public function remove($tree)
    {
        $path = explode('.', $tree);
        $this->removeInTree($this->tree, $path);
    }

    /**
     * Finds data using the dots notation and removes it from tree afterwards
     * @param string $tree
     * @return multiple
     */
    public function pluck($tree)
    {
        $result = $this->get($tree);
        $this->remove($tree);
        return $result;
    }

    /**
     * Registers all defined config files recursively
     * @return void
     */
    protected function registerConfigurationDirectory($basePath, $baseTree = '')
    {
        foreach (scandir($basePath) as $item) {
            if(!in_array($item, ['.','..'])){
                $tree = trim($baseTree . '.' . substr($item, 0, strpos($item, '.')), '.');
                $path = $basePath . DS . $item;
                if(is_dir($path)) $this->registerConfigurationDirectory($path, $tree);
                else $this->set($tree, include($path));
            }
        }
    }

    /**
     * Defines constants based on loaded config data
     * @return void
     */
    protected function registerConfigurationConstants()
    {
        if(!defined('DEBUG')) define('DEBUG', $this->get('app.debug'));
        if(!defined('THEME')) define('THEME', $this->get('site.theme'));
        if(!defined('THEME_PATH')) define('THEME_PATH', THEMES_PATH . DS . THEME);
    }

    /**
     * Searches and creates tree structure recursively for given path
     * @param array $tree
     * @param array $path
     * @param multiple $set
     * @return multiple
     */
    protected function registerInTree(&$tree, $path, $set = null)
    {
        $segment = array_shift($path);
        if(!isset($tree[$segment])) $tree[$segment] = [];
        if(!count($path)) {
            if(is_array($set)) $tree[$segment] = array_merge_recursive($tree[$segment], $set);
            else $tree[$segment] = $set;
            return $tree[$segment];
        }
        return $this->registerInTree($tree[$segment], $path, $set);
    }

    /**
     * Searches structure recursively for given path
     * @param array $tree
     * @param array $path
     * @return array
     */
    protected function findInTree(&$tree, $path)
    {
        $segment = array_shift($path);
        if(!isset($tree[$segment])) return;
        if(!count($path)) return $tree[$segment];
        return $this->findInTree($tree[$segment], $path);
    }

    /**
     * Searches structure recursively for given path and removes last segment
     * @param array $tree
     * @param array $path
     * @return void
     */
    protected function removeInTree(&$tree, $path)
    {
        $segment = array_shift($path);
        if(!isset($tree[$segment])) return;
        if(!count($path)) {
            unset($tree[$segment]);
            return;
        }
        return $this->removeInTree($tree[$segment], $path);
    }

}
