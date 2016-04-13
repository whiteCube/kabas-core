<?php

namespace Kabas\Config\FieldTypes;

class Textual extends Item
{
      protected $modified;

      public function condition()
      {
            return is_string($this->data);
      }

      public function __toString()
      {
            return $this->getText();
      }

      /**
       * Turn the string to uppercase characters.
       * @return $this
       */
      public function uppercase()
      {
            $this->modified = strtoupper($this->getText());
            return $this;
      }

      /**
       * Turn the string to lowercase characters.
       * @return $this
       */
      public function lowercase()
      {
            $this->modified = strtolower($this->getText());
            return $this;
      }

      /**
       * Escapes html entities
       * @return $this
       */
      public function escape()
      {
            $this->modified = htmlentities($this->getText());
            return $this;
      }

      public function contains($string, $caseSensitive = true)
      {
            if($caseSensitive) return strpos($this->getText(), $string) !== false;
            return stripos($this->getText(), $string) !== false;
      }

      /**
       * Cuts a string after a specified amount of characters,
       * without cutting inside a word. Appends triple dots.
       * @param  int $length
       * @param  string  $append
       * @return $this
       */
      public function length($length = 100, $append = "&nbsp;&hellip;")
      {
            $string = $this->getText();
            if (strlen($string) > $length) {
                  $string = wordwrap($string, $length, '\break');
                  $string = explode('\break', $string, 2);
                  $string = $string[0] . $append;
            }
            $this->modified = $string;
            return $this;
      }

      /**
       * Get the modified string if it has been modified, otherwise
       * get the original data.
       * @return string
       */
      public function getText()
      {
            return isset($this->modified) ? $this->modified : $this->data;
      }
}
