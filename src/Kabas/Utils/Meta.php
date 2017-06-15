<?php

namespace Kabas\Utils;

use Kabas\App;

class Meta
{
    /**
     * Set a meta information about the page
     * @param string $key
     * @param string $value
     */
    static function set($key, $value)
    {
        $page = App::content()->pages->getCurrent();
        $page->meta[$key] = $value;
    }

    /**
     * Get a meta information about the page
     * @param  string $key
     * @return string
     */
    static function get($key)
    {
        $page = App::content()->pages->getCurrent();
        if(isset($page->meta[$key])) return $page->meta[$key];
        return null;
    }

    /**
     * Get an array containing all metadata
     * @return array | null
     */
    static function all()
    {
        $page = App::content()->pages->getCurrent();
        if(!isset($page->meta)) return;
        return $page->meta;
    }

    /**
     * Output all the meta information in html tags
     * @return void
     */
    static function output()
    {
        foreach(self::all() as $name => $content) {
            echo '<meta name="' . $name . '" content="' . $content . '">';
        }
    }
}
