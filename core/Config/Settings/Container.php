<?php

namespace Kabas\Config\Settings;

class Container extends \Kabas\Config\Container
{
      public function __construct()
      {
            $this->loadConfigs();
            var_dump($this);
      }

      private function loadConfigs()
      {
            global $app;
            $this->database = $app->driver->loadDBConfig();
            $this->site = $app->driver->loadSiteConfig();
            $this->social = $app->driver->loadSocialConfig();
      }

}
