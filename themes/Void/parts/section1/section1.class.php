<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;

class Section1 extends BaseController
{
      public function setup($options)
      {
            $this->data->options = $options;
      }
}
