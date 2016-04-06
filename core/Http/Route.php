<?php

namespace Kabas\Http;

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
      protected $parameters = [];

      public function __construct($page)
      {
            $this->template = $page->template;
            $this->string = $page->route;
            $this->regex = $this->generateRegex();
            $this->pageID = $page->id;
      }

      /**
       * Generate a regex to match this route.
       * @return string
       */
      protected function generateRegex()
      {
            if($this->string === '') return '/^\s*$/';

            $regex =  trim(strtolower($this->string), '/');
            preg_match_all('/\{(.[^\{\}\/]+)\}/', $regex, $matches);
            if(isset($matches[1])) {
                  foreach($matches[1] as $parameter) {
                        $this->parameters[] = $parameter;
                  }
            }
            $regex = str_replace($matches[0], '(.[^\/]?)', str_replace('/', '\/', $regex));
            $regex = '/^\/' . $regex . '\/?$/';

            return $regex;
      }

      /**
       * Check if this route matches the specified route.
       * @param  string $route
       * @return bool
       */
      public function matches($route)
      {
            return !!preg_match_all($this->regex, $route, $matches);
      }

      /**
       * Get the list of parameters for the specified route.
       * @param  string $route
       * @return array
       */
      public function getParams($route)
      {
            preg_match_all($this->regex, $route, $matches);
            if(!empty($matches[1])) $this->parameters = array_combine($this->parameters, $matches[1]);
            return $this->parameters;
      }
}
