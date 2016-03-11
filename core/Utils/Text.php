<?php

namespace Kabas\Utils;

class Text
{
      /**
       * Formats a string to namespace conventional format
       *
       * @param  string $text
       * @return string
       */
      static function toNamespace($text)
      {
            $formattedString = $text;

            $text = str_replace('-', ' ', $text);
            $text = str_replace('_', ' ', $text);
            $text = str_replace('.', ' ', $text);

            $str = str_replace(' ', '', ucwords($text));
            $str[0] = strtoupper($str[0]);

            return $str;
      }
}
