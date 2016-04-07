<?php

namespace Theme\Void\Pages;

use Kabas\App;
use Kabas\Controller\BaseController;
use Theme\Void\Models\News as NewsModel;

class News extends BaseController
{
      protected function setup()
      {

            if(App::request()->isPost()){
                  $this->saveNews();
            }

            $this->data->news = NewsModel::orderBy('id', 'desc')->get();
      }

      public function saveNews()
      {
            $news = new NewsModel;
            $news->title = App::request()->post->title;
            $news->content = App::request()->post->content;
            $news->image = [
                  'src' => App::request()->post->imageSrc,
                  'alt' => App::request()->post->imageAlt
            ];
            $news->save();
      }
}
