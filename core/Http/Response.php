<?php

namespace Kabas\Http;

use Kabas\Utils\Text;
use \Kabas\App;

class Response
{
      protected $headers = [];
      protected $code;

      /**
       * Send a response to the browser
       * @return void
       */
      public function init($pageID)
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

      /**
       * Set HTTP headers for the response.
       * @param  array $headers
       * @return $this
       */
      public function headers($headers)
      {
            $this->headers = $headers;
            return $this;
      }

      /**
       * Set the HTTP response code.
       * @param  int $code
       * @return $this
       */
      public function code($code)
      {
            $this->code = $code;
            return $this;
      }

      /**
       * Send the previously built response.
       * @param  object $response
       * @return void
       */
      public function send($response)
      {
            if(!is_null($response)) return $response->run();
      }

      /**
       * Apply the predefined headers and response code.
       * @return void
       */
      protected function setHeaders()
      {
            if($this->code) http_response_code($this->code);
            foreach($this->headers as $header){
                  header($header);
            }
      }
}
