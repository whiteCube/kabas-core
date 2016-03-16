<?php

namespace Kabas\Drivers;

use \Kabas\Utils\File;

class Json
{
      /**
       * Loads the database configuration
       * @return object
       */
      public function loadDBConfig()
      {
            return File::loadJson('config/database.json');
      }

      /**
       * Loads the site configuration
       * @return object
       */
      public function loadSiteConfig()
      {
            return File::loadJson('config/site.json');
      }

      /**
       * Loads the social networks configuration
       * @return object
       */
      public function loadSocialConfig()
      {
            return File::loadJson('config/social.json');
      }
}
