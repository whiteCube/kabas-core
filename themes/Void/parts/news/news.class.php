<?php

namespace Theme\Void\Parts;

use \Kabas\Controller\BaseController;
use \Kabas\App;
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
            }
            $news = NewsModel::where('id', '>=', 1)->orderBy('id', 'desc')->limit(2)->get();
            $this->data->news = $news;
      }
}
