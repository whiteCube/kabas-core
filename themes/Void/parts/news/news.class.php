<?php

namespace Theme\Void\Parts;

use \Kabas\App;
use \Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;

class News extends BaseController
{
      public function setup()
      {
            $lol = NewsModel::find(4);
            $lol->title = 'News number 4!';
            $lol->save();

            $news = NewsModel::orderBy('id', 'desc')->limit(2)->get();
            $this->data->news = $news;
      }
}
