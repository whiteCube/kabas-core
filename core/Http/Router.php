<?php

namespace Kabas\Http;

class Router
{
      public function __construct()
      {
            $this->route = $_SERVER['REQUEST_URI'];
            $this->loadRoutes();
            $this->handle();
      }

      /**
       * Iterate through pages from config to create routes
       * @return void
       */
      private function loadRoutes()
      {
            global $app;
            $this->routes = [];

            foreach ($app->config->pages->items as $page) {
                  $this->routes[$page->route] = $page->id;
            }

      }

      /**
       * Check if current route exists and react
       * @return void
       */
      private function handle()
      {
            if(array_key_exists($this->route, $this->routes)) {
                  // We have a hit! the page id is located in $this->routes[$this->route]
            } else {
                  // 404
            }
      }

}
