<?php

namespace Kabas\Http;

use Kabas\Utils\Text;

use \Kabas\App;

class Response
{

      /**
       * Send a response to the browser
       *
       * @return void
       */
      public function send($pageID)
      {
            if($pageID === null){
                  if(array_key_exists('404', App::config()->pages->items)) {
                        http_response_code(404);
                        echo App::config()->pages->items['404']->data->content;
                        return;
                  } else {
                        http_response_code(404);
                        echo '404 par défaut';
                        return;
                  }
            } else {
                  $pageTemplate = Text::toNamespace(App::config()->pages->items[$pageID]->template);
                  $themeTemplate = '\Theme\\' . App::config()->settings->site->theme .'\Pages\\' . $pageTemplate;
                  $page = App::config()->pages->items[$pageID];
                  new $themeTemplate($page);
                  return;
            }
      }

}
