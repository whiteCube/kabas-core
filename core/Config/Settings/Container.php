<?php

namespace Kabas\Config\Settings;

use \Kabas\Kabas;

class Container extends \Kabas\Config\Container
{
      public function __construct()
      {
            $this->loadConfigs();
      }

      private function loadConfigs()
      {
            $app = Kabas::getInstance();
            $this->database = $app->driver->loadDBConfig();
            $this->site = $app->driver->loadSiteConfig();
            $this->social = $app->driver->loadSocialConfig();
      }

}
