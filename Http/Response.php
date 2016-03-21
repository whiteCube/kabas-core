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
            $app = App::getInstance();
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
                  $pageTemplate = Text::toNamespace($app->config->pages->items[$pageID]->template);
                  $themeTemplate = '\Theme\\' . $app->config->settings->site->theme .'\Pages\\' . $pageTemplate;
                  $page = $app->config->pages->items[$pageID];
                  new $themeTemplate($page);
                  return;
            }
      }

}
