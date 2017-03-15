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
      /**
       * Formats a string without namespace
       * @param  string $text
       * @return string
       */
      static function removeNamespace($text)
      {
            return substr($text, strrpos($text, '\\') + 1);
      }

      /**
       * Formats a text to a url-friendly string
       * @param  string $text
       * @return string
       */
      static function toSlug($text)
      {
            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
            $text = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $text);
            $text = strtolower(trim($text, '-'));
            $text = preg_replace("/[\/_| -]+/", '-', $text);
            return $text;
      }
}
