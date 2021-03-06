<?php

namespace Kabas\Http\Request;

use Kabas\App;
use Kabas\Config\LanguageRepository;

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

    /**
     * Application's available LanguageRepository
     * @var Kabas\Config\LanguageRepository
     */
    protected $locales;

    function __construct(LanguageRepository $locales, string $host, string $uri, string $script = null, $ssl = false)
    {
        $this->locales = $locales;
        $this->setSSL($ssl);
        $this->setHost($host);
        $this->setRoot($script);
        $this->setURI($uri);
    }

    public static function createFromServer()
    {
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $script = $_SERVER['SCRIPT_NAME'];
        $ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
        return new self(App::config()->languages, $host, $uri, $script, $ssl);
    }

    /**
     * Define protocol type
     * @param bool $secure 
     * @return void
     */
    public function setSSL($secure)
    {
        $this->scheme = $secure ? 'https' : 'http';
    }

    /**
     * Returns the query's protocol
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Define host string
     * @param string $host 
     * @return void
     */
    public function setHost($host)
    {
        $this->host = rtrim($host, '/');
    }

    /**
     * Returns the query's host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Define sub-directory string
     * @param ?string $script 
     * @return void
     */
    public function setRoot($script = null)
    {
        $this->root = is_null($script) ? null : $this->extractRootFromScript($script);
    }

    /**
     * Returns the query's root sub-directory
     * @return string|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Define full URI string, locale, and requested route
     * @param string $uri 
     * @return void
     */
    public function setURI($uri)
    {
        $this->uri = trim($this->extractRealURIFromURI($uri), '/');
        $this->locale = $this->extractLocaleFromRealURI($this->uri);
        $this->route = $this->extractRouteFromRealURI($this->uri, $this->locale);
    }

    /**
     * Returns the query's useful URI
     * @return string
     */
    public function getURI()
    {
        return $this->uri;
    }

    /**
     * Returns the query's locale
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Returns the query's requested route
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Returns the query's base URL
     * @return string
     */
    public function getBase()
    {
        $url = $this->getScheme() . '://';
        $url .= $this->getHost();
        $url .= ($this->getRoot() ? '/' . $this->getRoot() : '');
        return $url;
    }

    /**
     * Returns the query's full URL
     * @return string
     */
    public function getURL()
    {
        $url = $this->getBase();
        $url .= ($this->getLocale() ? '/' . $this->getLocale() : '');
        $url .= $this->getRoute();
        return rtrim($url, '/');
    }

    /**
     * Retrieves the sub-directory to Kabas' index file
     * @param string $script 
     * @return string|null
     */
    protected function extractRootFromScript($script)
    {
        preg_match('/(.+)?index.php$/', $script, $matches);
        if(!isset($matches[1])) return;
        if(!strlen($root = trim($matches[1], '/'))) return;
        return $root;
    }

    /**
     * Substracts sub-directory from given URI
     * @param string $uri 
     * @return string
     */
    protected function extractRealURIFromURI($uri)
    {
        $uri = trim(explode('?', $uri)[0], '/');
        if(!is_null($this->root) && strpos($uri, $this->root) === 0) {
            return substr($uri, strlen($this->root));
        }
        return $uri;
    }

    /**
     * Retrieves locale string in given URI
     * @param string $uri 
     * @return string|null
     */
    protected function extractLocaleFromRealURI($uri)
    {
        preg_match('/^\/?([^\/]+)?/', $uri, $matches);
        if(!isset($matches[1]) || !strlen($matches[1])) return;
        if(!$this->locales->has($matches[1])) return;
        return $matches[1];
    }

    /**
     * Substracts locale from given URI and formats remaing
     * string in order to make it machable for defined routes
     * @param string $uri 
     * @return string
     */
    protected function extractRouteFromRealURI($uri, $locale = null)
    {
        if(!$locale) return '/' . $uri;
        return '/' . substr($uri, strlen($locale) + 1);
    }
}