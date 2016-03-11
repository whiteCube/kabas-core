<?php

namespace Kabas\Drivers;

use \Kabas\Utils\File;

class Json
{
      public function loadDBConfig()
      {
            return File::loadJson('config/database.json');
      }

      public function loadSiteConfig()
      {
            return File::loadJson('config/site.json');
      }

      public function loadSocialConfig()
      {
            return File::loadJson('config/social.json');
      }
}
