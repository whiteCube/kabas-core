<?php

namespace Kabas\Http\Responses;

use Kabas\Utils\Url;
use Kabas\Http\Response;

class Redirect extends Response implements ResponseInterface
{
    public function __construct(string $target, array $params = [], $lang = null)
    {
        $this->target = $target;
        $this->params = $params;
        $this->lang = $lang;
    }

    /**
     * Executes the response. Called automatically.
     * @return void
     */
    public function run()
    {
        $this->setHeaders();
        header('Location: ' . Url::to($this->target, $this->params, $this->lang));
    }
}
