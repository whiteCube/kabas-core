<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use \Kabas\Utils\Assets;

class Section1 extends BaseController
{
      protected function setup()
      {
            $this->options->title->color = "rgb(41, 122, 140)";
            Assets::add('header', 'section1.css');
            Assets::add('footer', 'section1.js');
      }
}
