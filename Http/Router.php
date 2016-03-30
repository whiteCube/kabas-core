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
            $this->loadRoutes();
      }

      /**
       * Iterate through pages from config to create routes
       * @return void
       */
      public function loadRoutes()
      {
            foreach (App::config()->pages->items as $page) {
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
            $this->baseUrl = 'http://' . $_SERVER['SERVER_NAME'] . $pathToIgnore;
            if($pathToIgnore !== '') {
                  $route = explode($pathToIgnore, $_SERVER['REQUEST_URI'])[1];
            } else {
                  $route = $_SERVER['REQUEST_URI'];
            }
            $routeParts = explode('/', $route);
            $this->hasLangInUrl = false;
            foreach(App::config()->settings->site->lang->available as $lang) {
                  foreach($routeParts as $routePart) {
                        if($routePart === $lang) {
                              $this->hasLangInUrl = true;
                              App::config()->settings->site->lang->active = $lang;
                              $route = str_ireplace('/'.$lang, '', $route);
                              if($route === '') $route = '/';
                        }
                  }
            }
            return $route;
      }

      /**
       * Check if specified route exists in the application.
       * If no route is specified, checks the current one.
       * @param  string $route (optional)
       * @return boolean
       */
      public function routeExists($route = null)
      {
            if($route === null) $route = $this->route;
            return array_key_exists($route, $this->routes);
      }

      /**
       * Check if current route exists and return the corresponding page ID
       * @return string
       */
      public function getCurrentPageID()
      {
            if($this->routeExists()) {
                  return $this->routes[$this->route];
            } else return '404';
      }

      public function getCurrentPageTemplate()
      {
            if(isset(App::config()->pages->items[$this->getCurrentPageID()])) {
                  return App::config()->pages->items[$this->getCurrentPageID()]->template;
            }
      }

}
