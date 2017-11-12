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
    public $translator;

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


    /**
    * Creates a Translator instance for the Lang::trans() helper
    * @return Illuminate\Translation\Translator
    */
    protected function loadTranslator()
    {
        $locale = App::config()->languages->getCurrent()->original;
        $translationLoader = new FileLoader(new Filesystem, THEME_PATH . '/lang');
        return new Translator($translationLoader, $locale);
    }

    /**
    * Launches content fields parsing for all content types
    * @return void
    */
    public function parse()
    {
        self::setParsed(true);
        foreach ($this as $key => $container) {
            if($key === 'translator') continue;
            $container->parse();
        }
    }

    /**
    * Gets the parsed state for the content singleton
    * @return bool
    */
    public static function isParsed()
    {
        return self::$parsed;
    }

    /**
    * Sets the parsed state for the content singleton
    * @param bool $value
    * @return void
    */
    public static function setParsed($value)
    {
        self::$parsed = $value;
    }
}
