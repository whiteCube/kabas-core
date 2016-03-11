<?php

namespace Kabas\Http;

class Router
{
      /**
       * Routes for the current application
       * @var array
       */
      public $routes = [];

      public function __construct()
      {
            $this->route = $_SERVER['REQUEST_URI'];
            $this->loadRoutes();
      }

      /**
       * Iterate through pages from config to create routes
       * @return void
       */
      private function loadRoutes()
      {
            global $app;

            foreach ($app->config->pages->items as $page) {
                  $this->routes[$page->route] = $page->id;
            }

      }

      /**
       * Check if current route exists and return it
       * @return string
       */
      public function handle()
      {
            if(array_key_exists($this->route, $this->routes)) {
                  return $this->routes[$this->route];
            }
      }

}
