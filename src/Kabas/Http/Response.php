<?php

namespace Kabas\Http;

use \Kabas\App;
use \Kabas\View\View;

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
        $page = App::content()->pages->get($pageID);
        if(!$page) return View::notFound();
        $page->make();
    }

    /**
     * Add HTTP headers for the response.
     * @param  array $headers
     * @return $this
     */
    public function headers(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Returns the currently defined headers
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
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
