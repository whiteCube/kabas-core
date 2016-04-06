<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;
use Kabas\App;

class News extends BaseController
{
      protected function setup()
      {
            $this->data->news = NewsModel::orderBy('id', 'desc')->get();
      }
}
