<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;

class Section1 extends BaseController
{
      public function setup()
      {
            $this->data->options = $this->options;
      }
}
