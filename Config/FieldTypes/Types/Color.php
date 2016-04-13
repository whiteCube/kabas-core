<?php

namespace Kabas\Config\FieldTypes;

class Color extends Item
{
      public $type = "color";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return gettype($this->data) === 'string';
      }

      /**
       * Convert hex or rgb string to rgb object.
       * @return object
       */
      public function rgb()
      {
            if(strpos($this->data, ',') !== false) return $this->rgbStringToObject($this->data);

            $hex = str_replace("#", "", $this->data);

            if(strlen($hex) === 3) {
                  $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
                  $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
                  $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
            } else {
                  $r = hexdec(substr($hex, 0, 2));
                  $g = hexdec(substr($hex, 2, 2));
                  $b = hexdec(substr($hex, 4, 2));
            }

            $rgb = new \stdClass();
            $rgb->red = $r;
            $rgb->green = $g;
            $rgb->blue = $b;

            return $rgb;
      }

      /**
       * Convert rgb to hex
       * @return string
       */
      public function hex()
      {
            if(strpos($this->data, ',') === false) return $this->data;
            $rgb = $this->rgbStringToObject($this->data);

            $r = dechex($rgb->red);
            $g = dechex($rgb->green);
            $b = dechex($rgb->blue);

            return '#' . $r . $g . $b;
      }

      /**
       * Parse a rgb string and format it into an object
       * @param  string $rgbString
       * @return object
       */
      protected function rgbStringToObject($rgbString)
      {
            if(!is_string($rgbString)) return false;

            $values = str_replace(['rgb', '(', ')', ' '], '', $rgbString);
            $array = explode(',', $values);

            $o = new \stdClass();
            $o->red = $array[0];
            $o->green = $array[1];
            $o->blue = $array[2];

            return $o;
      }

      /**
       * Get the RGB red value of the color
       * @return string
       */
      public function red()
      {
            return $this->rgb()->red;
      }

      /**
       * Get the RGB green value of the color
       * @return string
       */
      public function green()
      {
            return $this->rgb()->green;
      }

      /**
       * Get the RGB blue value of the color
       * @return string
       */
      public function blue()
      {
            return $this->rgb()->blue;
      }

}
