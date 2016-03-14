<?php

namespace Kabas\Http;

use \Kabas\Kabas;

class Router
{
      /**
       * Routes for the current application
       * @var array
       */
      public $routes = [];

      public function __construct()
      {
            $this->loadRoutes();
      }

      /**
       * Iterate through pages from config to create routes
       * @return void
       */
      private function loadRoutes()
      {
            $app = Kabas::getInstance();

            foreach ($app->config->pages->items as $page) {
                  $this->routes[$page->route] = $page->id;
            }

      }

      /**
       * Get the current route
       * @return string
       */
      public function getRoute()
      {
            $pathToIgnore = explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
            if($pathToIgnore !== '') {
                  return explode($pathToIgnore, $_SERVER['REQUEST_URI'])[1];
            } else {
                  return $_SERVER['REQUEST_URI'];
            }
      }

      /**
       * Check if current route exists and return the corresponding page ID
       * @return string
       */
      public function handle()
      {
            $this->route = $this->getRoute();
            if($this->routeExists($this->route)) {
                  return $this->routes[$this->route];
            }
      }

      /**
       * Check if specified route exists in the application
       * @param  string $route
       * @return boolean
       */
      public function routeExists($route)
      {
            return array_key_exists($route, $this->routes);
      }

}
