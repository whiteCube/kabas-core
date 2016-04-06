<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Theme\Void\Models\News;
use Kabas\App;

class NewsItem extends BaseController
{
      protected function setup($id)
      {
            $this->data->news = News::find($id);
      }
}
