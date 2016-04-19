<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Exceptions\JsonException;

class File
{
      /**
       * Get the contents of a json file
       * @param  string $filePath
       * @return object the json data
       */
      static function loadJson($filePath)
      {
            if(file_exists($filePath)){
                  try {
                        $string = file_get_contents($filePath);
                        $json = json_decode($string);
                        if(!$json){
                              throw new JsonException($filePath, $string);
                        }
                  } catch (JsonException $e) {
                        echo $e->getMessage();
                        die();
                  }


                  return $json;
            }
      }

      /**
       * Writes data to a json file.
       * @param  mixed $data
       * @param  string $path
       * @return void
       */
      static function writeJson($data, $path)
      {
            file_put_contents($path . '.json', json_encode($data, JSON_PRETTY_PRINT));
      }

      /**
       * Write data to a file
       * @param  string $data
       * @param  string $path
       * @return void
       */
      static function write($data, $path)
      {
            file_put_contents($path, $data);
      }

      /**
       * Get the contents of a file
       * @param  string $path
       * @return string
       */
      static function read($path)
      {
            return file_get_contents($path);
      }

      static function deleteJson($path)
      {
            unlink($path . '.json');
      }

      /**
       * Get directory and subdirectory structure
       * @param  string $dirPath
       * @return array
       */
      static function parseDirectory($dirPath)
      {
            $data = scandir($dirPath);
            $items = [];

            foreach($data as $item) {
                  if($item !== '.' && $item !== '..') {
                        if(is_dir($dirPath . DS . $item)) {
                              $items[$item] = self::parseDirectory($dirPath . DS . $item);
                        } else {
                              $items[] = $item;
                        }
                  }
            }
            return $items;
      }

      /**
       * Read all files in directory and load their content if they're json
       * @param  string $dirPath
       * @return array
       */
      static function loadJsonFromDir($dirPath)
      {
            $data = scandir($dirPath);
            $items = [];

            foreach($data as $item) {
                  if($item !== '.' && $item !== '..') {
                        if(is_dir($dirPath . DS . $item)) {
                              $items[$item] = self::loadJsonFromDir($dirPath . DS . $item);
                        } else {
                              if(self::isJson($item)){
                                    $items[] = self::loadJson($dirPath . DS . $item);
                              }
                        }
                  }
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
            if($path_parts['extension'] === 'json') return true;
            return false;
      }

}
