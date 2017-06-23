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

    /**
     * Changes a string to uppercase characters
     * @param string $text
     * @return string
     */
    static function uppercase($text)
    {
        return strtoupper($text);
    }

    /**
     * Changes a string to lowercase characters
     * @param string $text 
     * @return string
     */
    static function lowercase($text)
    {
        return strtolower($text);
    }

    /**
     * Escapes html entities
     * @param string $text 
     * @return string
     */
    static function escape($text)
    {
        return htmlentities($text);
    }

    /**
     * Checks if output contains given substring
     * @param string $haystack 
     * @param string $needle 
     * @param bool $caseSensitive 
     * @return bool
     */
    static function contains($needle, $haystack, $caseSensitive = true)
    {
        if($caseSensitive) return (strpos($haystack, $needle) !== false);
        return (stripos($haystack, $needle) !== false);
    }

    /**
     * Cuts a string after a specified amount of characters.
     * Appends triple dots.
     * @param string $text
     * @param int $length
     * @param string $append
     * @return string
     */  
    static function cut($text, $length = 100, $append = '&nbsp;&hellip;')
    {
        $string = strip_tags($text);
        if(mb_strlen($string) > $length){
            $string = mb_substr($string, 0, $length);
            $string .= is_string($append) ? $append : '';
        }
        return $string;
    }

    /**
     * Cuts a string after a specified amount of characters,
     * without cutting inside a word. Appends triple dots.
     * @param  string $text
     * @param  int $length
     * @param  string $append
     * @return string
     */
    static function shorten($text, $length = 100, $append = '&nbsp;&hellip;')
    {
        $string = strip_tags($text);
        if (mb_strlen($string) > $length) {
            $string = wordwrap($string, $length, '\break');
            $string = explode('\break', $string, 2);
            $string = trim($string[0]) . $append;
        }
        return $string;
    }

    /**
     * Checks if string is longer than given length
     * @param string $text 
     * @param int $length 
     * @return bool
     */
    static function exceeds($text, $length)
    {
        return mb_strlen(strip_tags($text)) > $length;
    }
}
