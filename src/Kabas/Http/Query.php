<?php

namespace Kabas\Http;

use Kabas\App;

class Query
{
    /**
     * Request protocol
     * @var string
     */
    protected $scheme;

    /**
     * website's base host
     * without trailing slash
     * @var string
     */
    protected $host;

    /**
     * website's root
     * without slashes
     * @var string
     */
    protected $root;

    /**
     * Request's useful URI part
     * without slashes
     * @var string
     */
    protected $uri;

    /**
     * Requested language
     * @var string
     */
    protected $locale;

    /**
     * Requested URI route
     * with starting slash, without trailing slash
     * @var string
     */
    protected $route;

    function __construct(string $host, string $uri, string $script = null, $ssl = false)
    {
        $this->setSSL($ssl);
        $this->setHost($host);
        $this->setRoot($script);
        $this->setURI($uri);
    }

    public function setSSL($secure)
    {
        $this->scheme = $secure ? 'https' : 'http';
    }

    public function setHost($host)
    {
        $this->host = rtrim($host, '/');
    }

    public function setRoot($script = null)
    {
        $this->root = is_null($script) ? null : $this->extractRootFromScript($script);
    }

    public function setURI($uri)
    {
        $this->uri = trim($this->extractRealURIFromURI($uri), '/');
        $this->locale = $this->extractLocaleFromRealURI($this->uri);
        $this->route = $this->extractRouteFromRealURI($this->uri, $this->locale);
    }

    protected function extractRootFromScript($script)
    {
        preg_match('/(.+)?index.php$/', $script, $matches);
        if(!isset($matches[1])) return;
        if(!strlen($root = trim($matches[1], '/'))) return;
        return $root;
    }

    protected function extractRealURIFromURI($uri)
    {
        $uri = trim(explode('?', $uri)[0], '/');
        if(!is_null($this->root) && strpos($uri, $this->root) === 0) {
            return substr($uri, strlen($this->root));
        }
        return $uri;
    }

    protected function extractLocaleFromRealURI($uri)
    {
        preg_match('/^\/?([^\/]+)?/', $uri, $matches);
        if(!isset($matches[1]) || !strlen($matches[1])) return;
        // TODO : Use dependency injection for language repository ?
        if(!App::config()->languages->has($matches[1])) return;
        return $matches[1];
    }

    protected function extractRouteFromRealURI($uri, $locale = null)
    {
        if(!$locale) return '/' . $uri;
        return '/' . substr($uri, strlen($locale) + 1);
    }
}