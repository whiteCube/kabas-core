<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Kabas\App;
use Kabas\Utils\Session;

class Homepage extends BaseController
{
      // protected $view = 'home.php';
      protected function setup()
      {
            Session::flash('flash', 'message');
      }
}
