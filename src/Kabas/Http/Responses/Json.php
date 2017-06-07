<?php

namespace Kabas\Http\Responses;

use Kabas\Http\Response;

class Json extends Response
{
    protected $pretty = false;

    public function __construct($data)
    {
        $this->headers(['Content-Type: application/json']);
        $this->data = $data;
    }

    /**
     * Activate pretty-print on the JSON response.
     * Useful for debugging.
     * @return $this
     */
    public function pretty()
    {
        $this->pretty = true;
        return $this;
    }

    /**
     * Encodes the data to json.
     * @return void
     */
    protected function encodeData()
    {
        if($this->pretty) $this->data = json_encode($this->data, JSON_PRETTY_PRINT);
        else $this->data = json_encode($this->data);
    }

    /**
     * Executes the response. Called automatically.
     * @return void
     */
    public function run()
    {
        $this->encodeData();
        $this->setHeaders();
        echo $this->data;
    }
}
