<?php

namespace Theme\Void\Pages;

use Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;
use Kabas\App;

class NewsItem extends BaseController
{
      protected function setup($slug)
      {
            setlocale(LC_ALL, 'fr_BE');
            \Carbon\Carbon::setLocale('fr');
            $this->data->news = NewsModel::where('slug', $slug)->get();
            if(!$this->data->news) die('no matches, handled in controller');
      }
}
