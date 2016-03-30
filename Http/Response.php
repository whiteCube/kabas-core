<?php

namespace Kabas\Http;

use Kabas\Utils\Text;
use \Kabas\App;

class Response
{

      /**
       * Send a response to the browser
       * @return void
       */
      public function send($pageID)
      {
            if($pageID === null || $pageID === '404' || $pageID === '' ){
                  http_response_code(404);
                  if(array_key_exists('404', App::config()->pages->items)) {
                        $pageID = '404';
                  } else {
                        echo '404 par défaut';
                        return;
                  }
            }
            $page = App::config()->pages->items[$pageID];
            $pageTemplate = Text::toNamespace($page->template);
            $themeTemplate = '\Theme\\' . App::config()->settings->site->theme .'\Pages\\' . $pageTemplate;
            App::getInstance()->make($themeTemplate, [$page]);
            return;
      }

}
