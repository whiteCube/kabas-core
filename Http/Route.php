<?php

namespace Kabas\Http;

use \Kabas\App;

class Route
{
      /**
       * The string representation of this route.
       * ex: '/news/{id}'
       * @var string
       */
      public $string;

      /**
       * The regular expression to match this route's string.
       * @var string
       */
      protected $regex;

      /**
       * List of parameters in the route.
       * @var array
       */
      public $parameters = [];


      /**
       * Target page's identifier
       * @var object
       */
      public $page;

      public function __construct($page)
      {
            $this->page = $page->id;
            $this->string = $page->route;
            $this->regex = $this->generateRegex();
      }

      /**
       * Generate a regex to match this route.
       * @return string
       */
      protected function generateRegex()
      {
            if($this->string === '') return '/^\s*$/';
            $regex = trim(strtolower($this->string), '/');
            $regex = $this->parseParameters($regex);
            $regex = preg_replace('/([^\\\])\//', '$1\/', $regex);
            $regex = strlen($regex) ? '/^\/' . $regex . '\/?$/' : '/^\/?$/';
            return $regex;
      }

      protected function parseParameters($regex)
      {
            preg_match_all('/\{([a-zA-Z0-9]*)(?:::)?(\/.[^\/]+\/+)?(\?)?\}/', $regex, $a);
            foreach ($a[0] as $i => $param) {
                  $param = $this->getParameter($param, $a[1][$i], $a[2][$i], $a[3][$i]);
                  $regex = str_replace($param->string, $param->regex, $regex);
                  $this->parameters[] = $param;
            }
            return $regex;
      }

      protected function getParameter($string, $variable, $regex, $optional)
      {
            $o = new \stdClass();
            $o->string = $string;
            $o->variable = $variable;
            $o->isRequired = $optional === '?' ? false : true;
            $o->regex = strlen($regex) ? '(' . trim($regex,'/') . ')' : '(.[^\/]*)';
            if(!$o->isRequired) $o->regex .= '?';
            $o->value = null;
            return $o;
      }

      /**
       * Check if this route matches the specified route.
       * @param  string $route
       * @return bool
       */
      public function matches($route)
      {
            return !!preg_match($this->regex, $route);
      }

      /**
       * Get the list of parameters for the specified route.
       * If no route is specified, test current one.
       * @param  string $route
       * @return array
       */
      public function getParameters($route = null)
      {
            if(!$route) $route = App::router()->getRoute();
            preg_match($this->regex, $route, $matches);
            array_shift($matches);
            foreach ($matches as $i => $value) {
                  $this->parameters[$i]->value = urldecode($value);
            }
            return $this->parameters;
      }

      /**
       * Get this route's parameters in a key => value format
       * @return array
       */
      public function getParametersArray()
      {
            $params = [];
            foreach ($this->parameters as $param) {
                  $params[$param->variable] = $param->value;
            }
            return $params;
      }

}
