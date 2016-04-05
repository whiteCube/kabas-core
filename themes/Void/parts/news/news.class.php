<?php

namespace Theme\Void\Parts;

use \Kabas\App;
use \Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;

class News extends BaseController
{
      public function setup()
      {
            if(App::request()->isPost()) {
                  $article = new NewsModel;
                  $article->title = App::request()->post->title;
                  $article->content = App::request()->post->content;
                  $article->image = ['src' => App::request()->post->imageSrc, 'alt' => App::request()->post->imageAlt];
                  $article->save();
                  die();
            }
            $news = NewsModel::orderBy('id', 'desc')->get();
            $this->data->news = $news;
      }
}
