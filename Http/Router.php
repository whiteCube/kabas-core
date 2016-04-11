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

      /**
       * Contains routes that have already been regex validated
       * so they don't need to be regex'd again for performance
       * @var array
       */
      protected $previouslyChecked = [];

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
                  $this->routes[$page->route] = App::getInstance()->make('Kabas\Http\Route', [$page]);
            }
      }

      /**
       * Get the current route
       * @return string
       */
      public function getRoute()
      {
            $route = $this->removeBasePath();
            $route = $this->handleLang($route);
            return $route;
      }

      /**
       * Check for lang in URL.
       * @param  string $route
       * @return string
       */
      protected function handleLang($route)
      {
            $routeParts = explode('/', $route);
            $this->hasLangInUrl = false;
            foreach(App::config()->settings->site->lang->available as $lang) {
                  if(in_array($lang, $routeParts)) {
                        $this->hasLangInUrl = true;
                        App::config()->settings->site->lang->active = $lang;
                        $route = str_replace('/'. $lang, '', $route);
                        if($route === '') $route = '/';
                  }
            }
            return $route;
      }

      /**
       * Removes any subdirectories the CMS may be in.
       * @return string
       */
      protected function removeBasePath()
      {
            $basePath = explode('/index.php', $_SERVER['SCRIPT_NAME'])[0];
            $this->setBaseUrl($basePath);
            return str_replace($basePath, '', $_SERVER['REQUEST_URI']);
      }

      /**
       * Set the base URL to generate links later.
       * @param string $pathToIgnore
       */
      protected function setBaseUrl($basePath)
      {
            $ssl = !empty( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
            $protocol = 'http' . ($ssl ? 's' : '');
            $this->baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $basePath;
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
            if(!empty($this->previouslyChecked[$route])) return true;
            foreach($this->routes as $definedRoute) {
                  if($definedRoute->matches($route)) {
                        $this->routeWithParams = $route;
                        $this->route = $definedRoute->string;
                        $this->previouslyChecked[$this->route] = true;
                        return true;
                  }
            }
            return false;
      }

      public function getRouteById($id)
      {
            foreach ($this->routes as $route) {
                  if($route->pageID === $id) return $route;
            }
            return false;
      }

      /**
       * Check if current route exists and return the corresponding page ID
       * @return string
       */
      public function getCurrentPageID()
      {
            if($this->routeExists()) {
                  return $this->routes[$this->route]->pageID;
            } else return '404';
      }

      /**
       * Get the current page's template name.
       * @return string
       */
      public function getCurrentPageTemplate()
      {
            if(isset(App::config()->pages->items[$this->getCurrentPageID()])) {
                  return App::config()->pages->items[$this->getCurrentPageID()]->template;
            }
      }

      /**
       * Get the parameters from the url.
       * @return array
       */
      public function getParams()
      {
            $aParams = [];
            if($this->routeExists()) {
                  foreach ($this->routes[$this->route]->getRouteParameters($this->routeWithParams) as $param) {
                        $aParams[$param->variable] = $param->value;
                  }
            }
            return $aParams;
      }

}
