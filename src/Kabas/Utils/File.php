<?php

namespace Kabas\Utils;

use Kabas\Exceptions\JsonException;
use Kabas\Exceptions\FileNotFoundException;

class File
{
    protected static $useCache = true;
    protected static $cache = [];
    /**
     * Get the contents of a json file
     * @param  string $file
     * @param bool $cache
     * @throws FileNotFoundException if file does not exist on disk
     * @throws JsonException if json could not be decoded
     * @return object
     */
    static function loadJson($file, $cache = null)
    {
        if(isset(self::$cache[$file])) return self::$cache[$file];
        if(!file_exists($file)) throw new FileNotFoundException($file);
        $string = file_get_contents($file);
        $json = json_decode($string);
        if(!$json) throw new JsonException($file, $string);
        if($cache ?? self::$useCache) self::$cache[$file] = $json;
        return $json;
    }

    /**
     * Get the contents of a json file without throwing exceptions
     * @param  string $file
     * @return object|null
     */
    static function loadJsonIfValid($file, $cache = null)
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
        self::mkdir(dirname($path));
        file_put_contents($path, $data);
    }

    /**
     * Get the contents of a file
     * @param  string $path
     * @return string
     */
    static function read($path, $cache = null)
    {
        if(isset(self::$cache[$path])) return self::$cache[$path];
        $content = file_get_contents($path);
        if($cache ?? self::$useCache) self::$cache[$path] = $content;
        return $content;
    }

    /**
     * Erases a file from disk.
     * @param string $path 
     * @return void
     */
    static function delete($path)
    {
        unset(self::$cache[$path]);
        unlink($path);
    }

    /**
     * Creates a copy of given file and returns its new location
     * @param string $source 
     * @param string $destination 
     * @param bool $overwrite 
     * @return string
     */
    static function copy($source, $destination, $overwrite = true)
    {
        if(is_dir($source)) return self::copyDir($source, $destination);
        if(!$overwrite && file_exists($destination)) return $destination;
        self::mkdir(dirname($destination));
        $copied = copy($source, $destination);
        return $copied ? $destination : false;
    }

    /**
     * Creates given directory if necessary, with permissions
     * @param string $directory
     * @return bool
     */
    static function mkdir($directory)
    {
        if(is_dir($directory)) return true;
        return mkdir($directory, 0755, true);
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
                    continue;
                }
                $items[] = $item;
            }
        }
        return $items;
    }

    /**
     * Returns an associative array containing
     * content from all valid JSON files for given directory
     * @param  string $path
     * @param  boolean $recursive
     * @param  boolean $cache
     * @return array
     */
    static function loadJsonFromDir($path, $recursive = false, $cache = null)
    {
        $items = [];
        foreach (static::scanJsonFromDir($path, $recursive) as $name => $file) {
            if(DEBUG) {
                $items[$name] = File::loadJson($file, $cache);
                continue;
            }
            // @codeCoverageIgnoreStart
            if($content = static::loadJsonIfValid($file, $cache)) $items[$name] = $content;
            // @codeCoverageIgnoreEnd
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
        $parts = pathinfo($path);
        return $parts['extension'] === 'json';
    }

    static function copyDir($source, $destination)
    {
        $dir = opendir($source);
        @mkdir($destination);
        while(false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    self::copyDir($source . '/' . $file,$destination . '/' . $file);
                }
                else {
                    copy($source . '/' . $file,$destination . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

}
