<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Theme\Void\Models\News;
use Kabas\App;

class NewsItem extends BaseController
{
      protected function setup($slug)
      {
            $this->data->news = News::where('slug', $slug)->get();
      }
}
