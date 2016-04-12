<?php

namespace Kabas\Http\Responses;

use Kabas\Http\Response;

class Json extends Response
{
      public function __construct($data)
      {
            $this->headers(['Content-Type: application/json']);
            $this->data = json_encode($data);
      }

      public function run()
      {
            $this->setHeaders();
            echo $this->data;
      }
}
