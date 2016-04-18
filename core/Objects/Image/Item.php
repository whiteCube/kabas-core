<?php

namespace Kabas\Objects\Image;

use Kabas\Utils\Image;
use Kabas\Utils\Url;
use Kabas\App;

class Item
{
      public $filename;
      public $extension;
      public $path;
      public $file;
      protected $editor;


      public function __construct($file)
      {
            if(is_array($file)) $file = (object) $file;
            $cond = (strpos($file->src, 'http://') !== false && strpos($file->src, 'http://') === 0) ||
                    (strpos($file->src, 'https://') !== false && strpos($file->src, 'https://') === 0);
            if($cond) {
                  $this->file = $file->src;
            } else {
                  $nameParts = explode('.', $file->src);
                  $this->filename = $nameParts[0];
                  $this->extension = $nameParts[1];
                  $this->path = THEME_PATH . DS . 'assets' . DS . 'img' . DS;
            }
            $this->file = $file->src;
            $this->alt = $file->alt;
      }

      public function __call($name, $args)
      {
            $this->makeEditor();
            call_user_func_array([$this->editor, $name], $args);
            return $this;
      }

      public function filesize()
      {
            $this->makeEditor();
            return $this->editor->intervention->filesize();
      }

      public function getCore()
      {
            $this->makeEditor();
            return $this->editor->intervention->getCore();
      }

      public function height()
      {
            $this->makeEditor();
            return $this->editor->intervention->height();
      }

      public function iptc($key = null)
      {
            $this->makeEditor();
            return $this->editor->intervention->iptc($key);
      }

      public function mime()
      {
            $this->makeEditor();
            return $this->editor->intervention->mime();
      }

      public function pickColor($x, $y, $format = null)
      {
            $this->makeEditor();
            return $this->editor->intervention->pickColor($x, $y, $format);
      }

      public function width()
      {
            $this->makeEditor();
            return $this->editor->intervention->width();
      }

      public function apply()
      {
            if($this->editor) $this->file = $this->editor->save();
            return $this;
      }

      public function show($echo = true)
      {
            $s = '<img src="' . $this->src() . '" alt="' . $this->alt() . '" />';
            if($echo) echo($s);
            return $s;
      }

      public function alt()
      {
            if(isset($this->alt)) return $this->alt;
            return null;
      }

      public function src()
      {
            if(strpos($this->file, 'http://') !== false && strpos($this->file, 'http://') === 0) {
                  return $this->file;
            } else {
                  if(get_class($this) !== 'Kabas\Objects\Image\Item') $image = $this->file;
                  else $image = $this;
                  return Url::base() . '/themes/' . App::theme() . '/assets/img/' . $image->file;
            }
      }

      protected function makeEditor()
      {
            if(!$this->editor) $this->editor = App::getInstance()->make('Kabas\Objects\Image\Editor', [$this->path . $this->file]);
      }
}
