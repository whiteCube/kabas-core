<?php

namespace Kabas\Http;

use \Kabas\App;
use \Kabas\Utils\Lang;

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
      public $lang;

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

      public function __construct()
      {
            $this->subdirectory = $this->getSubdirectory();
            $this->rootURL = $this->getRootURL();
            $this->baseURL = $this->getBaseURL();
            $this->query = $this->getQuery($_SERVER['REQUEST_URI']);
            $this->route = $this->getCleanQuery($this->query)->route;
            $this->setLang();
      }

      /**
       * Loads content-defined routes
       * @return object $this
       */
      public function load()
      {
            foreach (App::content()->pages->items as $page) {
                  $this->routes[] = App::getInstance()->make('Kabas\Http\Route', [$page]);
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
       * Retrieves the path from URI
       * @param  string $uri
       * @return string
       */
      protected function getQuery($uri)
      {
            $s = trim(substr($uri, strlen($this->subdirectory)),'/');
            if(!strlen($s)) return '/';
            return '/' . $s . '/';
      }

      /**
       * Get the lang-cleared route
       * @return object
       */
      public function getCleanQuery($uri, $hasSet = true)
      {
            preg_match('/^\/([^\/]+)?/', $uri, $a);
            $o = new \stdClass();
            $o->lang = null;
            $o->route = null;
            if(isset($a[1]) && $lang = Lang::is($a[1])){
                  $o->lang = $lang;
                  $o->route = substr($uri, strlen($a[0]));
                  if($hasSet) $this->lang = $lang;
            }
            else{
                  $o->route = $uri;
            }
            return $o;
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
            if($lang = Lang::is($lang)) return $lang;
            return Lang::getDefault();
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
            $this->current->gatherParameters();
            return $this;
      }

      /**
       * Returns route that matches the query
       * @param string $route
       * @return string
       */
      public function findMatchingRoute($route)
      {
            if(array_key_exists($route, $this->cache)) return $this->cache[$route];
            $this->cache[$route] = false;
            foreach($this->routes as $item) {
                  if($item->matches($route)) {
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
            $url = $this->parseUrl($url);
            if($url->base == $this->baseURL) return $url->route;
            return false;
      }

      /**
       * Returns useful information about given URL
       * @param  string $url
       * @return object
       */
      public function parseUrl($url)
      {
            $a = parse_url($url);
            $o = new \stdClass();
            $o->root = isset($a['scheme']) ? $a['scheme'] . '://' : '';
            $o->root .= isset($a['host']) ? $a['host'] : '';
            $o->root .= isset($a['port']) ? ':' . $a['port'] : '';
            $o->base = false;
            $o->query = false;
            $o->lang = false;
            $o->route = false;
            if(isset($a['path'])){
                  $o->base = $o->root;
                  if(strlen($this->subdirectory) && strpos($a['path'], $this->subdirectory) === 0){
                        $o->base .= $this->subdirectory;
                        $o->query = $this->getQuery($a['path']);
                  }
                  else $o->query = $a['path'];
                  $o->base .= '/';
                  $q = $this->getCleanQuery($o->query, false);
                  $o->route = $q->route;
                  if($q->lang) $o->lang = $q->lang;
            }
            return $o;
      }

}
