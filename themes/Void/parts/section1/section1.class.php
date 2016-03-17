<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use \Kabas\Utils\Assets;

class Section1 extends BaseController
{
      public function setup()
      {
            $this->options->title->color = "rgb(41, 122, 140)";
            Assets::required('header', 'section1.css');
      }
}
