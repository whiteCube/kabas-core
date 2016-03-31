<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use \Kabas\Utils\Model;

class News extends BaseController
{
      public function setup()
      {
            $model = Model::get('news');
            $this->data->news = $model->getAll();
      }
}
