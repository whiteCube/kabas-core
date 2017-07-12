<?php

namespace Kabas\Content;

use Kabas\App;

use Kabas\Content\Menus\Container as Menus;
use Kabas\Content\Pages\Container as Pages;
use Kabas\Content\Options\Container as Options;
use Kabas\Content\Partials\Container as Partials;
use Kabas\Content\Administrators\Container as Administrators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class Container
{
    public $pages;
    public $partials;
    public $menus;
    public $options;
    public $administrators;

    protected static $parsed = false;

    public function __construct(Pages $pages, Partials $partials, Menus $menus, Options $options, Administrators $administrators)
    {
        $this->pages = $pages;
        $this->partials = $partials;
        $this->menus = $menus;
        $this->options = $options;
        $this->administrators = $administrators;
        $this->translator = $this->loadTranslator();
    }

    protected function loadTranslator()
    {
        $locale = App::config()->languages->getCurrent()->original;
        $path = THEME_PATH . '/lang';
        $translationLoader = new FileLoader(new Filesystem, $path);
        return new Translator($translationLoader, $locale);
    }

    public function parse()
    {
        self::setParsed(true);
        foreach ($this as $key => $container) {
            if($key === 'translator') continue;
            var_dump($key);
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
