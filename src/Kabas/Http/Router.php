<?php

namespace Kabas\Http;

use Kabas\App;
use Kabas\Utils\Lang;
use Kabas\Exceptions\NotFoundException;

class Router
{
    protected $rootURL;

    protected $subdirectory;

    protected $baseURL;

    protected $query;

    protected $route;

    /**
     * Current matching route
     * @var object
     */
    protected $current;

    /**
     * Routes for the current application
     * @var array
     */
    protected $routes = [];

    /**
     * Contains routes that have already been regex validated
     * so they don't need to be regex'd again for performance
     * @var array
     */
    protected $cache = [];

    public function __construct(UrlWorker $urlWorker)
    {
        $this->urlWorker = $urlWorker;
    }

    /**
     * Loads content-defined routes
     * @return object $this
     */
    public function load()
    {
        foreach (App::content()->pages->getItems() as $id => $aggregate) {
            $this->routes[] = new Route($id, $aggregate);
        }
        return $this;
    }

    /**
     * Analyses the current incoming request
     * @return object $this
     */
    public function boot()
    {
        $this->rootURL = $this->getRootURL();
        $this->baseURL = $this->getBaseURL();
        $this->query = $this->urlWorker->getQuery($_SERVER['REQUEST_URI']);
        $query = $this->urlWorker->getCleanQuery($this->query);
        $this->route = $query->route;
        Lang::set($query->lang ? $query->lang : $this->detectLang());
        return $this;
    }

    /**
     * Get the current route query
     * @return void
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Returns all routes
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get the current route query
     * @return void
     */

    public function getBase()
    {
        return $this->baseURL;
    }

    /**
     * Retrieves the domain's root URL
     * @return string
     */

    protected function getRootURL()
    {
        $ssl = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
        return 'http' . ($ssl ? 's' : '') . '://' . rtrim($_SERVER['HTTP_HOST'],'/');
    }

    /**
     * Builds the base URL to Kabas's root
     * @return string
     */

    protected function getBaseURL()
    {
        $this->subdirectory = $this->urlWorker->setSubdirectory();
        return $this->rootURL . $this->subdirectory . '/';
    }

    /**
     * Tries to get the browser's language.
     * @return string
     */
    protected function detectLang()
    {
        $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        return $lang[0];
    }

    /**
     * Check if specified route exists in the application.
     * @param  string $route (optional)
     * @return boolean
     */
    public function routeExists($route = null)
    {
        return $this->findMatchingRoute($route) !== false;
    }

    /**
     * Defines the current route
     * @return object $this
     */
    public function setCurrent()
    {
        if(!($this->current = $this->findMatchingRoute($this->route))) {
            throw new NotFoundException($this->route, 'page', 404);
        }
        $this->current->gatherParameters($this->route, Lang::getCurrent()->original);
        return $this;
    }

    /**
     * Returns route that matches the query for current language
     * @param string $route
     * @return string
     */
    public function findMatchingRoute($route)
    {
        if(array_key_exists($route, $this->cache)) return $this->cache[$route];
        $this->cache[$route] = false;
        $language = Lang::getCurrent();
        foreach($this->routes as $item) {
            if($item->matches($route, $language)) {
                $this->cache[$route] = $item;
                return $item;
            }
        }
        return false;
    }

    public function getRouteByPage($id)
    {
        foreach ($this->routes as $route) {
            if($route->page === $id) return $route;
        }
        return false;
    }

    /**
     * Returns the currently matching route
     * @return object
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Returns the usable route from an URL
     * @param  string $url
     * @return string
     */
    public function extractRoute($url)
    {
        $url = $this->urlWorker->parseUrl($url);
        if($url->base == $this->baseURL) return $url->route;
        return false;
    }

}
