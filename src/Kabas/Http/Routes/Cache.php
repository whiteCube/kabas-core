<?php

namespace Kabas\Http\Routes;

use Kabas\App;

class Cache {

    /**
     * Currently cached localized URIs
     * @var array
     */
    protected $items = [];

    /**
     * Adds a localized URL to the cached items
     * @param string $uri
     * @param string $locale
     * @param ?\Kabas\Http\Routes\Routes\Route $route
     * @return void
     */
    public function set($uri, $locale, Route $route = null)
    {
        $identifier = $this->getIdentifier($uri, $locale);
        $this->items[$identifier] = is_null($route) ? null : $route->getSignature();
    }

    /**
     * Returns a cached route signature
     * for given URL and locale
     * @param string $uri
     * @param string $locale
     * @return string|null
     */
    public function get($uri, $locale)
    {
        return $this->items[$this->getIdentifier($uri, $locale)] ?? null;
    }

    /**
     * Formats given URL and locale into an unique
     * cache identification signature
     * @param string $uri
     * @param string $locale
     * @return void
     */
    protected function getIdentifier($uri, $locale)
    {
        return strtoupper($locale) . '::' . trim($uri, '/');
    }

}