<?php

namespace Kabas\Objects\Image;

use Intervention\Image\ImageManager as Intervention;
use Kabas\App;

class Editor
{
      public $intervention;

      protected $dirname;

      protected $filename;

      protected $extension;

      protected $file;

      protected $history = [];

      function __construct($dir, $file, $extension)
      {
            $this->dirname = $dir;
            $this->filename = $file;
            $this->extension = $extension;
      }

      public function backup($name = null)
      {
            $this->history[] = $this->getAction('backup', func_get_args());
      }

      public function blur($amount)
      {
            $this->history[] = $this->getAction('blur', func_get_args(), 'blur' . $amount);
      }

      public function brightness($level)
      {
            $this->history[] = $this->getAction('brightness', func_get_args(), 'brightness' . $level);
      }

      public function circle($diameter, $x, $y, $callback = null)
      {
            $this->history[] = $this->getAction('circle', func_get_args(), 'circleX' . $x . 'Y' . $y);
      }

      public function colorize($red, $green, $blue)
      {
            $this->history[] = $this->getAction('colorize', func_get_args(), 'r' . $red . 'g' . $green . 'b' . $blue);
      }

      public function contrast($level)
      {
            $this->history[] = $this->getAction('contrast', func_get_args(), 'contrast' . $level);
      }

      public function crop($width, $height, $x = null, $y = null)
      {
            $this->history[] = $this->getAction('crop', func_get_args(), 'crop' . $width . 'x' . $height);
      }

      public function destroy()
      {
            $this->history[] = $this->getAction('destroy', func_get_args());
      }

      public function ellipse($width, $height, $x, $y, $callback = null)
      {
            $this->history[] = $this->getAction('ellipse', func_get_args(), 'ellipseX' . $x . 'Y' . $y);
      }

      public function encode($format, $quality = null)
      {
            $this->history[] = $this->getAction('encode', func_get_args());
      }

      public function exif($key)
      {
            $this->history[] = $this->getAction('exif', func_get_args());
      }

      public function fill($filling, $x = null, $y = null)
      {
            $this->history[] = $this->getAction('fill', func_get_args());
      }

      public function filter($filter)
      {
            $this->history[] = $this->getAction('filter', func_get_args(), 'filter-' . get_class($filter));
      }

      public function flip($mode)
      {
            $this->history[] = $this->getAction('flip', func_get_args(), 'flip-' . $mode);
      }

      public function fit($width, $height)
      {
            $this->history[] = $this->getAction('fit', func_get_args(), $width . 'x' . $height);
      }

      public function gamma($correction)
      {
            $this->history[] = $this->getAction('gamma', func_get_args(), 'gamma' . $correction);
      }

      public function greyscale()
      {
            $this->history[] = $this->getAction('greyscale', func_get_args());
      }

      public function heighten($height, $callback)
      {
            $this->history[] = $this->getAction('heighten', func_get_args(), 'height' . $height);
      }

      public function insert($source, $position = null, $x = null, $y = null)
      {
            $this->history[] = $this->getAction('insert', func_get_args());
      }

      public function interlace($interlace = null)
      {
            $this->history[] = $this->getAction('interlace', func_get_args());
      }

      public function invert()
      {
            $this->history[] = $this->getAction('invert', func_get_args());
      }

      public function limitColors($count, $matte = null)
      {
            $this->history[] = $this->getAction('limitColors', func_get_args(), $count . 'colors');
      }

      public function line($x1, $y1, $x2, $y2, $callback = null)
      {
            $this->history[] = $this->getAction('line', func_get_args());
      }

      public function mask($source, $mask_with_alpha = null)
      {
            $this->history[] = $this->getAction('mask', func_get_args());
      }

      public function opacity($transparency)
      {
            $this->history[] = $this->getAction('opacity', func_get_args(), 'opacity' . $transparency);
      }

      public function orientate()
      {
            $this->history[] = $this->getAction('orientate', func_get_args());
      }

      public function pixel($color, $x, $y)
      {
            $this->history[] = $this->getAction('pixel', func_get_args());
      }

      public function pixelate($size)
      {
            $this->history[] = $this->getAction('pixelate', func_get_args());
      }

      public function polygon($points, $callback = null)
      {
            $this->history[] = $this->getAction('polygon', func_get_args());
      }

      public function rectangle($x1, $y1, $x2, $y2, $callback = null)
      {
            $this->history[] = $this->getAction('rectangle', func_get_args());
      }

      public function reset($name = null)
      {
            $this->history[] = $this->getAction('reset', func_get_args());
      }

      public function resize($width, $height, $callback = null)
      {
            $this->history[] = $this->getAction('resize', func_get_args(), $width . 'x' . $height);
      }

      public function resizeCanvas($width, $height, $anchor = null, $relative = null, $bgcolor = null)
      {
            $this->history[] = $this->getAction('resizeCanvas', func_get_args(), $width . 'x' . $height);
      }

      public function rotate($angle, $bgcolor = null)
      {
            $this->history[] = $this->getAction('rotate', func_get_args(), 'rotate' . $angle);
      }

      public function hasChanges()
      {
            if (count($this->history)) return true;
            return false;
      }

      public function save()
      {
            $this->setFile();
            if(!file_exists($this->dirname . DS . $this->file)) {
                  $this->executeActions();
                  $this->intervention->save($this->dirname . DS . $this->file);
            }
            $this->history = [];
            return $this->file;
      }

      public function sharpen($amount)
      {
            $this->history[] = $this->getAction('sharpen', func_get_args(), 'sharpen' . $amount);
      }

      public function text($text, $x = null, $y = null, $callback = null)
      {
            $this->history[] = $this->getAction('text', func_get_args());
      }

      public function trim($base, $away = null, $tolerance = null, $feather = null)
      {
            $this->history[] = $this->getAction('trim', func_get_args());
      }

      public function widen($width, $callback = null)
      {
            $this->history[] = $this->getAction('widen', func_get_args(), 'widen' . $width);
      }

      protected function setFile()
      {
            $this->file = $this->filename . $this->getHistoryString() . '.' . $this->extension;
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
            $this->prepareIntervention();
            if(!isset($this->intervention->filename)) {
                  $this->intervention->filename = $this->filename;
                  $this->intervention->extension = $this->extension;
                  $this->intervention->dirname = $this->dirname;
            }
            foreach ($this->history as $o) {
                  $this->intervention = call_user_func_array([$this->intervention, $o->action], $o->args);
            }
      }

      public function prepareIntervention()
      {
            if(!$this->intervention){
                  $this->intervention = App::getInstance()->make(
                        'Intervention\Image\ImageManager',
                        [['driver' => App::config()->appConfig['imageDriver']]]
                  );
                  $this->intervention = $this->intervention->make($this->dirname . DS . $this->filename . '.' . $this->extension);
            }
      }
}
