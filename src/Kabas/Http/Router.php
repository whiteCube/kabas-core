<?php

namespace Kabas\Http;

use Kabas\App;
use Kabas\Utils\Lang;

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
     * 404 route
     * @var object
     */
    protected $notFound;

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
        $this->rootURL = $this->getRootURL();
        $this->baseURL = $this->getBaseURL();
        $this->query = $this->urlWorker->getQuery($_SERVER['REQUEST_URI']);
        $query = $this->urlWorker->getCleanQuery($this->query);
        $this->route = $query->route;
        Lang::set($query->lang ? $query->lang : $this->detectLang());
    }

    /**
     * Loads content-defined routes
     * @return object $this
     */
    public function load()
    {
        foreach (App::content()->pages->items as $id => $aggregate) {
            $this->routes[] = new Route($id, $aggregate);
        }
        $this->notFound = App::getInstance()->make('Kabas\Http\RouteNotFound');
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
        if($this->findMatchingRoute($route) !== false) return true;
        return false;
    }

    /**
     * Defines the current route
     * @return object $this
     */
    public function setCurrent()
    {
        $this->current = $this->findMatchingRoute($this->route);
        if($this->current) $this->current->gatherParameters();
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
        if($this->current === false) return $this->get404();
        return $this->current;
    }

    /**
     * Returns the NotFound route
     * @return object
     */
    public function get404()
    {
        return $this->notFound;
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
