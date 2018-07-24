<?php

namespace Kabas\Http\Routes;

use Kabas\Utils\Lang;
use Kabas\Http\Request;
use Kabas\Exceptions\NotFoundException;

class Router
{

    /**
     * Routes Repository
     * @var \Kabas\Http\Routes\Routes\RouteRepository
     */
    protected $repository;

    /**
     * Routes Url worker
     * @var \Kabas\Http\Request
     */
    protected $request;

    /**
     * Current matching route
     * @var object
     */
    protected $current;

    public function __construct(Request $request, RouteRepository $repository)
    {
        $this->repository = $repository;
        $this->request = $request;
    }

    /**
     * Loads content-defined routes
     * @return object $this
     */
    public function load()
    {
        // TODO : should not be necessary anymore when
        // RouteRepository will have its content injected
        $this->repository->loadFromContent();
        return $this;
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
        if(!$locale) {
            $locale = $this->request->getLocale()->getCurrent()->original;
        }
        return !is_null($this->repository->find($uri, $locale));
    }

    /**
     * Defines the current route
     * @return object $this
     */
    public function setCurrent()
    {
        // TODO : use request method too in order to define current route.
        $route = $this->request->getQuery()->getRoute();
        $locale = $this->request->getLocale()->getCurrent()->original;
        if(!($this->current = $this->repository->find($route, $locale))) {
            throw new NotFoundException($route, 'page', 404);
        }
        $this->current->gatherParameters($route, $locale);
        return $this;
    }

    /**
     * Retrieves a route by its signature
     * @return object
     */
    public function getRouteByPage($id)
    {
        // TODO : should not exist, and be replaced with getRepository()->get($id)
        // or with a magic call on repository
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

}
