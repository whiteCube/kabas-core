<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use \Kabas\Utils\Meta;

class Header extends BaseController
{
      protected function setup()
      {
            Meta::set('og:image', 'test.png');
      }
}
