<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;

class Header extends BaseController
{
      public function setup($options)
      {
            $this->data->options = $options;
      }
}
