<?php

namespace Kabas\Http;

use Kabas\App;
use Kabas\Http\Request\Query;
use Kabas\Http\Request\Locale;

class Request
{
    /**
     * The request method.
     * @var string
     */
    protected $method;

    /**
     * The request query.
     * @var Kabas\Http\Request\Query
     */
    protected $query;

    /**
     * The request locale.
     * @var Kabas\Http\Request\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->query = Query::createFromServer();
        $this->locale = new Locale(App::config()->languages, $this->query);
        $this->constructData();
    }

    /**
     * Check if request is POST
     * @return boolean
     */
    public function isPost()
    {
        return $this->method === 'POST';
    }

    /**
     * Check if request is GET
     * @return boolean
     */
    public function isGet()
    {
        return $this->method === 'GET';
    }

    /**
     * Get the request method.
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the request query.
     * @return Kabas\Http\Request\Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the request locale.
     * @return Kabas\Http\Request\Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Casts $_GET and $_POST as objects
     * @return void
     */
    protected function constructData()
    {
        $this->get = (object) $_GET;
        $this->post = (object) $_POST;
    }
}
