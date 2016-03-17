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

            $this->detectLang();
      }

      protected function detectLang()
      {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
            if(in_array($lang, $this->site->lang->available)){
                  $this->site->lang = $lang;
            } else {
                  $this->site->lang = $this->site->lang->default;
            }
      }

}
