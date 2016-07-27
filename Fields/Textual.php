<?php

namespace Kabas\Fields;

class Textual extends Item
{
      public function condition()
      {
            return is_string($this->value);
      }

      /**
       * Turn the string to uppercase characters.
       * @return $this
       */
      public function uppercase()
      {
            $this->output = strtoupper($this->output);
            return $this;
      }

      /**
       * Turn the string to lowercase characters.
       * @return $this
       */
      public function lowercase()
      {
            $this->output = strtolower($this->output);
            return $this;
      }

      /**
       * Escapes html entities
       * @return $this
       */
      public function escape()
      {
            $this->output = htmlentities($this->output);
            return $this;
      }

      /**
       * Checks if output contains given substring
       * @return boolean
       */
      public function contains($string, $caseSensitive = true)
      {
            if($caseSensitive) return (strpos($this->output, $string) !== false);
            return (stripos($this->output, $string) !== false);
      }

      /**
       * Cuts a string after a specified amount of characters.
       * Appends triple dots.
       * @param  int $length
       * @param  string $append
       * @return $this
       */
      public function cut($length = 100, $append = "&nbsp;&hellip;")
      {
            $string = strip_tags($this->output);
            if(mb_strlen($string) > $length){
                  $string = mb_substr($string, 0, $length);
                  $string .= is_string($append) ? $append : '';
            }
            $this->output = $string;
            return $this;
      }

      /**
       * Cuts a string after a specified amount of characters,
       * without cutting inside a word. Appends triple dots.
       * @param  int $length
       * @param  string $append
       * @return $this
       */
      public function shorten($length = 100, $append = "&nbsp;&hellip;")
      {
            $string = strip_tags($this->output);
            if (mb_strlen($string) > $length) {
                  $string = wordwrap($string, $length, '\break');
                  $string = explode('\break', $string, 2);
                  $string = trim($string[0]) . $append;
            }
            $this->output = $string;
            return $this;
      }
}
