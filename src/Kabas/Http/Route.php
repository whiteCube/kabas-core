<?php

namespace Kabas\Http;

use Kabas\App;
use Kabas\Utils\Lang;

class Route
{
    /**
     * The string representations of this route.
     * ex: '/news/{id}'
     * @var array
     */
    public $strings = [];

    /**
     * The regular expressions to match this route's strings.
     * @var array
     */
    protected $regexen = [];

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

    public function __construct($page, $aggregate)
    {
        $this->page = $page;
        $this->strings = $this->makeStringsArray($aggregate);
        $this->regexen = $this->makeRegexenArray();
    }

    /**
     * Make the strings array containing each available language
     * @param array $aggregate
     * @return array
     */
    protected function makeStringsArray(array $aggregate)
    {
        $strings = [];
        foreach ($aggregate as $lang => $page) {
            $strings[$lang] = $page->route;
        }
        return $strings;
    }

    /**
     * Make the regexen array containing each available language
     * @return array
     */
    protected function makeRegexenArray()
    {
        $regexen = [];
        foreach ($this->strings as $lang => $route) {
            $regexen[$lang] = $this->generateRegex($route);
        }
        return $regexen;
    }

    /**
     * Generate a regex to match given route.
     * @param string $route
     * @return string
     */
    protected function generateRegex(string $route)
    {
        if($route === '') return '/^\s*$/';
        $regex = trim($route, '/');
        $regex = $this->upgradeParamsToRegex($regex);
        $regex = preg_replace('/([^\\\])\//', '$1\/', $regex);
        $regex = strlen($regex) ? '/^\/' . $regex . '\/?$/' : '/^\/?$/';
        return $regex;
    }

    protected function upgradeParamsToRegex($regex)
    {
        preg_match_all('/\{([a-zA-Z0-9]*)(?:::)?(\/.[^\/]+\/+)?(\?)?\}/', $regex, $a);
        foreach ($a[0] as $i => $param) {
            $param = $this->makeParameter($param, $a[1][$i], $a[2][$i], $a[3][$i]);
            $regex = str_replace($param->string, $param->regex, $regex);
            $this->parameters[] = $param;
        }
        return $regex;
    }

    protected function makeParameter($string, $variable, $regex, $optional)
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
     * @param  Kabas\Config\Language $lang
     * @return bool
     */
    public function matches($route, $lang)
    {
        if(!isset($this->regexen[$lang->original])) return false;
        return !!preg_match($this->regexen[$lang->original], $route);
    }

    /**
     * Retrieves the parameters for the current route.
     * @return void
     */
    public function gatherParameters($route = null)
    {
        $route = App::router()->getRoute();
        preg_match($this->regexen[Lang::getCurrent()->original], $route, $matches);
        array_shift($matches);
        foreach ($matches as $i => $value) {
            $this->parameters[$i]->value = urldecode($value);
        }
    }

    /**
     * Get this route's parameters in a key => value format
     * @return array
     */
    public function getParameters()
    {
        $params = [];
        foreach ($this->parameters as $param) {
            $params[$param->variable] = $param->value;
        }
        return $params;
    }

}
