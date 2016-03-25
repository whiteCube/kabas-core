<?php

namespace Kabas\Objects\Image;

class Item
{
      public $filename;
      public $extension;
      public $path;
      public $file;
      protected $editor;


      public function __construct($file)
      {
            $nameParts = explode('.', $file);
            $this->filename = $nameParts[0];
            $this->extension = $nameParts[1];
            $this->file = $file;
            $this->path = THEME_PATH . DS . 'assets' . DS . 'img' . DS;
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

      protected function makeEditor()
      {
            if(!$this->editor) $this->editor = new Editor($this->path . $this->file);
      }
}
