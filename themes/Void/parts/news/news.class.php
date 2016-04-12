<?php

namespace Theme\Void\Parts;

use \Kabas\App;
use \Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;

class News extends BaseController
{
      public function setup()
      {
            setlocale(LC_ALL, 'fr_BE');
            \Carbon\Carbon::setLocale('fr');
            $news = NewsModel::orderBy('id', 'desc')->limit(2)->get();
            $this->data->news = $news;
      }
}
