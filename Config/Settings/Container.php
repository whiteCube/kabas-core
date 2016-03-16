<?php

namespace Kabas\Config\Settings;

use \Kabas\App;

class Container
{
      public function __construct()
      {
            $this->loadConfigs();
      }

      /**
       * Load all configs
       * @return void
       */
      private function loadConfigs()
      {
            $app = App::getInstance();
            $this->database = $app->driver->loadDBConfig();
            $this->site = $app->driver->loadSiteConfig();
            $this->social = $app->driver->loadSocialConfig();
      }

}
