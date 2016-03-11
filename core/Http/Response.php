<?php

namespace Kabas\Http;

class Response
{

      /**
       * Send a response to the browser
       *
       * @return void
       */
      public function send($pageID)
      {
            global $app;
            if($pageID === null){
                  if(array_key_exists('404', $app->config->pages->items)) {
                        http_response_code(404);
                        echo $app->config->pages->items['404']->data->content;
                        return;
                  } else {
                        http_response_code(404);
                        echo '404 par défaut';
                        return;
                  }
            } else {
                  echo $app->config->pages->items[$pageID]->data->content;
                  return;
            }
      }
}
