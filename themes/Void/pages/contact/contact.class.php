<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Kabas\Utils\Session;

class Contact extends BaseController
{
      public function setup()
      {
            if(Session::hasFlash()){
                  var_dump(Session::getFlash('flash'));
            }
      }
}
