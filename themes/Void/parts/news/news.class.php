<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;

class News extends BaseController
{
      public function setup()
      {
            $news = NewsModel::all();
            $this->data->news = $news;
      }
}
