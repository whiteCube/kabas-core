<?php

namespace Kabas\Config\FieldTypes;

use Kabas\Objects\Image\Item as ImageItem;
use Kabas\App;

class Image extends Item
{
      public $type = "image";
      public $file;

      public function __construct($fieldName = null, $data = null)
      {
            parent::__construct($fieldName, $data);
            if($this->data) {
                  $this->file = new ImageItem($this->data);
            }
      }

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return true;
      }

      public function __call($name, $args)
      {
            return call_user_func_array([$this->file, $name], $args);
      }





}
