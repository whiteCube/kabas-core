<?php

namespace Kabas\Config\Settings;

class Container extends \Kabas\Config\Container
{
      public function __construct()
      {
            $this->loadConfigs();
      }

      private function loadConfigs()
      {
            global $app;
            $this->database = $app->driver->loadDBConfig();
            $this->site = $app->driver->loadSiteConfig();
            $this->social = $app->driver->loadSocialConfig();
      }

}
