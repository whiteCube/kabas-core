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
            $this->database = json_decode(json_encode(include __DIR__ . '/../../../config/database.php'));
            $this->site = json_decode(json_encode(include __DIR__ . '/../../../config/site.php'));
            $this->social = json_decode(json_encode(include __DIR__ . '/../../../config/social.php'));

            $this->detectLang();
      }

      /**
       * Detect browser language and set it as site lang.
       * If site doesn't support said lang, set it to the default one.
       * @return void
       */
      protected function detectLang()
      {
            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
            if(in_array($lang, $this->site->lang->available)){
                  $this->site->lang->active = $lang;
            } else {
                  $this->site->lang->active = $this->site->lang->default;
            }
      }

}
