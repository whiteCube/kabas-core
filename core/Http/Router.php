<?php

namespace Kabas\Http;

use \Kabas\App;

class Router
{
      protected $rootURL;

      protected $baseURL;

      protected $subdirectory;

      protected $query;

      protected $route;

      /**
       * Lang code of current request
       * as defined in /config/site
       * @var string
       */
      public $lang = false;

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
      protected $matchesCache = [];

      public function __construct()
      {
            $this->subdirectory = $this->getSubdirectory();
            $this->rootURL = $this->getRootURL();
            $this->baseURL = $this->getBaseURL();
            $this->query = $this->getQuery();
            $this->route = $this->getCleanQuery();
            $this->setLang();
      }

      /**
       * Loads defined the routes
       * @return void
       */
      public function init()
      {
            foreach (App::content()->pages->items as $page) {
                  $this->routes[] = App::getInstance()->make('Kabas\Http\Route', [$page]);
            }
            $this->notFound = App::getInstance()->make('Kabas\Http\RouteNotFound');
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
       * Get the current route query
       * @return void
       */

      public function getBase()
      {
            return $this->baseURL;
      }

      /**
       * Retrieves the subdirectory the CMS may be in.
       * @return string
       */

      protected function getSubdirectory()
      {
            preg_match('/(.+)?index.php$/', $_SERVER['SCRIPT_NAME'], $a);
            if(strlen($a[1]) > 1) return (substr($a[1], 0, 1) == '/' ? '' : '/') . rtrim($a[1], '/');
            return '';
      }

      /**
       * Retrieves the domain's root URL
       * @return string
       */

      protected function getRootURL()
      {
            $ssl = !empty( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
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
       * Retrieves the "route" from current request
       * @return string
       */
      protected function getQuery()
      {
            $s = trim(substr($_SERVER['REQUEST_URI'], strlen($this->subdirectory)),'/');
            if(!strlen($s)) return '/';
            return '/' . $s . '/';
      }

      /**
       * Get the lang-cleared route
       * @return string
       */
      public function getCleanQuery()
      {
            preg_match('/^\/([^\/]+)?/', $this->query, $a);
            if(isset($a[1]) && in_array($a[1], App::config()->settings->site->lang->available)){
                  $this->lang = $a[1];
                  return substr($this->query, strlen($a[0]));
            }
            return $this->query;
      }

      /**
       * Sets automatic lang if request didn't
       * contain language information
       * @return string
       */

      protected function setLang()
      {
            if(!$this->lang) $this->lang = $this->detectLang();
      }

      /**
       * Trys to get the browser's language.
       * If site doesn't support said lang, returns the default one.
       * @return string
       */
      protected function detectLang()
      {
            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
            if(in_array($lang, App::config()->settings->site->lang->available)) return $lang;
            return App::config()->settings->site->lang->default;
      }

      /**
       * Check if specified route exists in the application.
       * @param  string $route (optional)
       * @return boolean
       */
      public function routeExists($route = null)
      {
            if($this->getMatchingRoute($route) !== false) return true;
            return false;
      }

      /**
       * Returns route that matches the query
       * If no query is specified, checks the current one.
       * @return string
       */
      public function getMatchingRoute($route = null)
      {
            if($route === null) $route = $this->route;
            if(array_key_exists($route, $this->matchesCache)) return $this->matchesCache[$route];
            $this->matchesCache[$route] = false;
            foreach($this->routes as $item) {
                  if($item->matches($route)) {
                        $this->matchesCache[$route] = $item;
                        return $item;
                  }
            }
            return false;
      }

      public function getRouteById($id)
      {
            foreach ($this->routes as $route) {
                  if($route->page === $id) return $route;
            }
            return false;
      }

      /**
       * Finds or/and returns the currently matching route
       * @return object
       */
      public function getCurrent()
      {
            if(is_null($this->current)) $this->current = $this->getMatchingRoute();
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

}
