<?php

namespace Kabas\Config\Settings;

use \Kabas\App;

class Container extends \Kabas\Config\Container
{
      public function __construct()
      {
            $this->loadConfigs();
      }

      private function loadConfigs()
      {
            $app = App::getInstance();
            $this->database = $app->driver->loadDBConfig();
            $this->site = $app->driver->loadSiteConfig();
            $this->social = $app->driver->loadSocialConfig();
      }

}
