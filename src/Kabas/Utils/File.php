<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Exceptions\JsonException;
use Kabas\Exceptions\FileNotFoundException;

class File
{
    protected static $cache = [];
    /**
     * Get the contents of a json file
     * @param  string $file
     * @return object
     */
    static function loadJson($file, $cache = true)
    {
        if(isset(self::$cache[$file])) return self::$cache[$file];
        if(!file_exists($file)) throw new FileNotFoundException($file);
        $string = file_get_contents($file);
        $json = json_decode($string);
        if(!$json) throw new JsonException($file, $string);
        if($cache) self::$cache[$file] = $json;
        return $json;
    }

    /**
     * Get the contents of a json file without throwing exceptions
     * @param  string $file
     * @return object|null
     */
    static function loadJsonIfValid($file, $cache = true)
    {
        try {
            $content = self::loadJson($file, $cache);
        } catch (\Exception $e) {
            return;
        }
        return $content;
    }

    /**
     * Writes data to a json file.
     * @param  mixed $data
     * @param  string $path
     * @return void
     */
    static function writeJson($data, $path)
    {
        self::write(json_encode($data, JSON_PRETTY_PRINT), $path . '.json');
    }

    /**
     * Write data to a file
     * @param  string $data
     * @param  string $path
     * @return void
     */
    static function write($data, $path)
    {
        if(isset(self::$cache[$path])) self::$cache[$path] = $data;
        file_put_contents($path, $data);
    }

    /**
     * Get the contents of a file
     * @param  string $path
     * @return string
     */
    static function read($path, $cache = true)
    {
        if(isset(self::$cache[$path])) return self::$cache[$path];
        $content = file_get_contents($path);
        if($cache) self::$cache[$path] = $content;
        return $content;
    }

    static function deleteJson($path)
    {
        unset(self::$cache[$path]);
        unlink($path . '.json');
    }

    /**
     * Get directory and subdirectory structure
     * @param  string $path
     * @return array
     */
    static function parseDirectory($path)
    {
        $data = scandir($path);
        $items = [];

        foreach($data as $item) {
            if($item !== '.' && $item !== '..') {
                if(is_dir($path . DS . $item)) {
                    $items[$item] = self::parseDirectory($path . DS . $item);
                } else {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }

    /**
     * Returns an associative array containing
     * content from all valid JSON files for given directory
     * @param  string $path
     * @param  boolean $recursive
     * @return array
     */
    static function loadJsonFromDir($path, $recursive = false, $cache = true)
    {
        $items = [];
        foreach (static::scanJsonFromDir($path, $recursive) as $name => $file) {
            if(App::config()->get('app.debug')) {
                $items[$name] = File::loadJson($file, $cache);
            } else {
                if($content = static::loadJsonIfValid($file, $cache)) $items[$name] = $content;
            }
        }
        return $items;
    }

    /**
     * Returns an associative array containing
     * all JSON files for given directory.
     * @param  string $path
     * @param  boolean $recursive
     * @return array
     */
    static function scanJsonFromDir($path, $recursive = false)
    {
        $items = [];
        if(!($path = realpath($path))) return $items;
        foreach (scandir($path) as $item) {
            if(in_array($item, ['.', '..'])) continue;
            $item = $path . DS . $item;
            if(is_dir($item)) {
                if($recursive) $items = array_merge($items, static::scanJsonFromDir($item, true));
                continue;
            }
            $info = pathinfo($item);
            if(!isset($info['extension']) || $info['extension'] != 'json') continue;
            $items[$info['filename']] = $item;
        }
        return $items;
    }

    /**
     * Check if file has .json extension
     * @param  string  $path
     * @return boolean
     */
    static function isJson($path)
    {
        $path_parts = pathinfo($path);
        return $path_parts['extension'] === 'json';
    }

}
