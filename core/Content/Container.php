<?php

namespace Kabas\Content;

class Container
{
      public $pages;
      public $partials;
      public $menus;

      public function __construct(Pages\Container $pages, Partials\Container $partials, Menus\Container $menus)
      {
            $this->pages = $pages;
            $this->partials = $partials;
            $this->menus = $menus;
      }
}
