<?php

namespace Kabas\Http;

use \Kabas\App;
use Kabas\View\View;
use Kabas\Utils\Text;

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
            $page = App::config()->pages->getPage($pageID);

            if(!$page) return View::notFound();

            $controllerName = Text::toNamespace($page->template);
            $pageController = '\Theme\\' . App::theme() .'\Pages\\' . $controllerName;
            App::getInstance()->make($pageController, [$page]);
      }

      /**
       * Add HTTP headers for the response.
       * @param  array $headers
       * @return $this
       */
      public function headers($headers)
      {
            $this->headers += $headers;
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
            else throw new \Exception('No response defined');
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
