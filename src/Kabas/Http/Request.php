<?php

namespace Kabas\Http;

use Kabas\Http\Request\Query;

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

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = Query::createFromServer();
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
    public function method()
    {
        return $this->method;
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
