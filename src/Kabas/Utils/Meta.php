<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Utils\Url;

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
        if(isset($page->meta[$key])) return self::clean($page->meta[$key]);
    }

    /**
     * Get an array containing all metadata
     * @return array | null
     */
    static function all()
    {
        $page = App::content()->pages->getCurrent();
        if(!isset($page->meta)) return;
        ksort($page->meta);
        return array_map(function($item) {
            return self::clean($item);
        }, $page->meta);
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

    protected static function clean($content)
    {
        return str_replace(['#ROOT#','#CURRENT#'], [Url::base(),Url::getCurrent()], trim($content));
    }
}
