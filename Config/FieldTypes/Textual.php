<?php

namespace Kabas\Config\FieldTypes;

class Textual extends Item
{
      public function condition($value)
      {
            return is_string($value);
      }

      public function uppercase()
      {
            return strtoupper($this->data);
      }

      public function lowercase()
      {
            return strtolower($this->data);
      }

      /**
       * Cuts a string after a specified amount of characters,
       * without cutting inside a word. Appends triple dots.
       * @param  int $length
       * @param  string  $append
       * @return string
       */
      public function length($length = 100, $append = "&nbsp;&hellip;")
      {
            $string = trim($this->data);
            if (strlen($string) > $length) {
                  $string = wordwrap($string, $length, '\break');
                  $string = explode('\break', $string, 2);
                  $string = $string[0] . $append;
            }
            return $string;
      }
}
