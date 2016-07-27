<?php

namespace Kabas\Utils;

class Text
{
      /**
       * Formats a string to namespace conventional format
       * @param  string $text
       * @return string
       */
      static function toNamespace($text)
      {
            $text = str_replace('-', ' ', $text);
            $text = str_replace('_', ' ', $text);
            $text = str_replace('.', ' ', $text);
            $text = str_replace(' ', '', ucwords($text));
            return ucfirst($text);
      }
}
