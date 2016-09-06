<?php

namespace Kabas\Content;

class Container
{
      public $pages;
      public $partials;
      public $menus;

      protected static $parsed = false;

      public function __construct(Pages\Container $pages, Partials\Container $partials, Menus\Container $menus)
      {
            $this->pages = $pages;
            $this->partials = $partials;
            $this->menus = $menus;
      }

      public function parse()
      {
            foreach ($this as $key => $container) {
                  $container->parse();
            }
            self::setParsed(true);
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
