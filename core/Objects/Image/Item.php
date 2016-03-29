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
            if(strpos($file->src, 'http://') !== false && strpos($file->src, 'http://') === 0) {
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

      public function fit($width, $height)
      {
            $this->makeEditor();
            $this->editor->fit(intval($width), intval($height));
            return $this;
      }


      public function greyscale()
      {
            $this->makeEditor();
            $this->editor->greyscale();
            return $this;
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
                  return Url::base() . '/themes/' . App::config()->settings->site->theme . '/assets/img/' . $image->file;
            }
      }

      protected function makeEditor()
      {
            if(!$this->editor) $this->editor = new Editor($this->path . $this->file);
      }
}
