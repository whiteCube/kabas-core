<?php

namespace Kabas\Http;

use \Kabas\App;

class Router
{
      /**
       * Routes for the current application
       * @var array
       */
      public $routes = [];

      public function __construct()
      {
            $this->route = $this->getRoute();
      }

      /**
       * Iterate through pages from config to create routes
       * @return void
       */
      public function loadRoutes()
      {
            $app = App::getInstance();

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
            $app = App::getInstance();
            $pathToIgnore = explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
            $this->baseUrl = 'http://' . $_SERVER['SERVER_NAME'] . $pathToIgnore;
            if($pathToIgnore !== '') {
                  $route = explode($pathToIgnore, $_SERVER['REQUEST_URI'])[1];
            } else {
                  $route = $_SERVER['REQUEST_URI'];
            }
            $routeParts = explode('/', $route);
            $this->hasLangInUrl = false;
            foreach($app->config->settings->site->lang->available as $lang) {
                  foreach($routeParts as $routePart) {
                        if($routePart === $lang) {
                              $this->hasLangInUrl = true;
                              $app->config->settings->site->lang->active = $lang;
                              $route = str_ireplace('/'.$lang, '', $route);
                              if($route === '') $route = '/';
                        }
                  }
            }
            return $route;
      }

      /**
       * Check if current route exists and return the corresponding page ID
       * @return string
       */
      public function handle()
      {
            if($this->routeExists($this->route)) {
                  return $this->getCurrentPageID();
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

      public function getCurrentPageID()
      {
            return $this->routes[$this->route];
      }

      public function getCurrentPageTemplate()
      {
            $app = App::getInstance();
            return $app->config->pages->items[$this->getCurrentPageID()]->template;
      }

}
