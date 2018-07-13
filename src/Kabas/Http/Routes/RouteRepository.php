<?php

namespace Kabas\Http\Routes;

use Kabas\App;

class RouteRepository {

    /**
     * Routes for the current application
     * @var array
     */
    protected $routes = [];

    /**
     * Already captured URLs associated to
     * their matching route signatures
     * @var \Kabas\Http\Routes\Routes\Cache
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = new Cache();
        // TODO : inject content repository for automatic routes loading
        // Currently handled with $this->loadFromContent();
    }

    /**
     * Creates a new route into the repository
     * @param string $namespace
     * @param string $name
     * @param array $definition
     * @return \Kabas\Http\Routes\Routes\Route
     */
    public function register($namespace, $name, array $definition)
    {
        $route = new Route($namespace, $name, $definition);
        $this->set($route);
        return $route;
    }

    /**
     * Defines a route into the repository
     * @param \Kabas\Http\Routes\Routes\Route $route
     * @return void
     */
    public function set(Route $route)
    {
        $this->routes[$route->getSignature()] = $route;
    }

    /**
     * Returns route for given signature
     * @param string $signature
     * @return \Kabas\Http\Routes\Routes\Route|null
     */
    public function get($signature)
    {
        return $this->routes[$signature] ?? null;
    }

    /**
     * Registers all content-defined routes
     * @return void
     */
    public function loadFromContent()
    {
        foreach (App::content()->pages->getItems() as $name => $aggregate) {
            $definition = $this->getDefinitionFromContentAggregate($aggregate);
            $this->register(null, $name, $definition);
        }
    }

    /**
     * Extracts route definition from content locales aggregate
     * @param array $aggregate
     * @return array
     */
    protected function getDefinitionFromContentAggregate(array $aggregate)
    {
        $definition = [];
        foreach ($aggregate as $locale => $page) {
            $definition[$locale] = $page->route;
        }
        return $definition;
    }

    /**
     * Returns matching route for given URL and locale
     * @param string $uri
     * @param string $locale
     * @return \Kabas\Http\Routes\Routes\Route|null
     */
    public function find($uri, $locale)
    {
        if($signature = $this->cache->get($uri, $locale)) {
            return $this->get($signature);
        }
        $route = $this->getMatchFromUri($uri, $locale);
        $this->cache->set($uri, $locale, $route);
        return $route;
    }

    /**
     * Searches for a matching route for given URI and locale
     * @param string $uri
     * @param string $locale
     * @return \Kabas\Http\Routes\Routes\Route|null
     */
    protected function getMatchFromUri($uri, $locale)
    {
        foreach ($this->routes as $route) {
            if($route->matches($uri, $locale)) return $route;
        }
    }

}
