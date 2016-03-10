<?php

namespace Kabas\Config\Settings;

use \Kabas\Utils\File;

class Container
{
      public function __construct()
      {
            $this->database = File::loadJson('config/database.json');
            $this->site = File::loadJson('config/site.json');
            $this->social = File::loadJson('config/social.json');
      }

}
