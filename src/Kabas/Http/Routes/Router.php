<?php

namespace Kabas\Http\Routes;

use Kabas\Utils\Lang;
use Kabas\Exceptions\NotFoundException;

class Router
{

    /**
     * Routes Repository
     * @var \Kabas\Http\Routes\Routes\RouteRepository;
     */
    protected $repository;

    /**
     * Routes Url worker
     * @var \Kabas\Http\Routes\Routes\UrlWorker;
     */
    protected $worker;

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

    public function __construct(UrlWorker $urlWorker)
    {
        // TODO : inject RouteRepository
        $this->repository = new RouteRepository();
        $this->worker = $urlWorker;
    }

    /**
     * Returns the URL worker
     * @return \Kabas\Http\Routes\UrlWorker
     */
    public function getWorker()
    {
        // TODO : this should probably not exist.
        // Only necessary for Kabas\Utils\Url which could
        // simply instanciate a new UrlWorker.
        return $this->worker;
    }

    /**
     * Loads content-defined routes
     * @return object $this
     */
    public function load()
    {
        // TODO : should not be necessary anymore when
        // RouteRepository will be injected
        $this->repository->loadFromContent();
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
        $this->query = $this->worker->getQuery($_SERVER['REQUEST_URI']);
        $query = $this->worker->getCleanQuery($this->query);
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
     * Get the URL base part
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
        $this->subdirectory = $this->worker->setSubdirectory();
        return $this->rootURL . $this->subdirectory . '/';
    }

    /**
     * Tries to get the browser's language.
     * @return string
     */
    protected function detectLang()
    {
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || !strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return;
        $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        return $lang[0];
    }

    /**
     * Check if specified URI has a matching
     * route in the application.
     * @param ?string $uri
     * @param ?string $locale
     * @return boolean
     */
    public function routeExists($uri = null, $locale = null)
    {
        // TODO : use Injected Language repository
        if(!$locale) $locale = Lang::getCurrent()->original;
        return !is_null($this->repository->find($uri, $locale));
    }

    /**
     * Defines the current route
     * @return object $this
     */
    public function setCurrent()
    {
        $locale = Lang::getCurrent()->original;
        if(!($this->current = $this->repository->find($this->route, $locale))) {
            throw new NotFoundException($this->route, 'page', 404);
        }
        $this->current->gatherParameters($this->route, $locale);
        return $this;
    }

    /**
     * Retrieves a route by its signature
     * @return object
     */
    public function getRouteByPage($id)
    {
        // TODO : should handle route namespaces correctly !
        return $this->repository->get($id);
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
        $url = $this->worker->parseUrl($url);
        if($url->base == $this->baseURL) return $url->route;
        return false;
    }

}
