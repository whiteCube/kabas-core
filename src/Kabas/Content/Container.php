<?php

namespace Kabas\Content;

class Container
{
    public $pages;
    public $partials;
    public $menus;

    protected static $parsed = false;

    public function __construct(Pages\Container $pages, Partials\Container $partials, Menus\Container $menus, Options\Container $options)
    {
        $this->pages = $pages;
        $this->partials = $partials;
        $this->menus = $menus;
        $this->options = $options;
    }

    public function parse()
    {
        self::setParsed(true);
        foreach ($this as $key => $container) {
            $container->parse();
        }
    }

    public static function isParsed()
    {
        return self::$parsed;
    }

    public static function setParsed($value)
    {
        self::$parsed = $value;
    }
}
