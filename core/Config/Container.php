<?php

namespace Kabas\Config;

class Container
{
      public function __construct()
      {
            $this->settings = new Settings\Container();
            $this->pages = new Pages\Container();
      }
}
