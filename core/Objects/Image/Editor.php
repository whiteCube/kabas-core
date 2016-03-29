<?php

namespace Kabas\Objects\Image;

use Intervention\Image\ImageManager as Intervention;

class Editor
{
      protected $history = [];
      protected $intervention;
      protected $lastFileName;

      function __construct($path)
      {
            $this->intervention = new Intervention(['driver' => 'imagick']);
            $this->intervention = $this->intervention->make($path);
            if(!isset($this->intervention->filename)) {
                  $file = explode('/', $path);
                  $exploded = explode('.', $file[count($file) - 1]);
                  $this->intervention->filename = $exploded[0];
                  $this->intervention->extension = $exploded[1];
                  $this->intervention->dirname = THEME_PATH . DS . 'assets' . DS . 'img' . DS;
            }
      }

      public function fit($width, $height)
      {
            array_push($this->history, $this->getAction('fit', ['width' => intval($width), 'height' => intval($height)], $width . 'x' . $height));
      }

      public function greyscale()
      {
            array_push($this->history, $this->getAction('greyscale', []));
      }

      public function save()
      {

            if(!$this->fileExists()) $this->executeActions()->save($this->intervention->dirname . DS . $this->lastFileName);
            $this->history = [];
            return $this->lastFileName;
      }

      protected function fileExists()
      {
            $this->lastFileName = $this->intervention->filename . $this->getHistoryString() . '.' . $this->intervention->extension;
            if(file_exists($this->intervention->dirname . DS . $this->lastFileName)) return true;
            return false;
      }

      protected function getHistoryString()
      {
            $s = '';
            foreach ($this->history as $o) {
                  $s .= '-' . $o->slug;
            }
            return $s;
      }

      protected function getAction($name, $args, $slug = false)
      {
            $o = new \stdClass();
            $o->action = $name;
            $o->args = $args;
            $o->slug = $slug ? $slug : $name;
            return $o;
      }

      protected function executeActions()
      {
            foreach ($this->history as $o) {
                  $this->intervention = call_user_func_array([$this->intervention, $o->action], $o->args);
            }
            return $this->intervention;
      }
}
