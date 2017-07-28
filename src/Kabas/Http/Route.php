<?php

namespace Kabas\Http;

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
     * @var string
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
        // Reset parameters indexes to numeric. This
        // way parameter values can be properly gathered.
        $this->parameters = array_values($this->parameters);
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
        $regex = strlen($regex) ? '/^\/?' . $regex . '\/?$/' : '/^\/?$/';
        return $regex;
    }

    protected function upgradeParamsToRegex($regex)
    {
        preg_match_all('/(\/)?\{([a-zA-Z0-9]*)(?:::)?(\/.[^\/]+\/+)?(\?)?\}/', $regex, $a);
        foreach ($a[0] as $i => $param) {
            $param = $this->makeParameter($param, $a[1][$i], $a[2][$i], $a[3][$i], $a[4][$i]);
            $regex = str_replace($param->string, $param->regex, $regex);
            $this->parameters[$param->variable] = $param;
        }
        return $regex;
    }

    protected function makeParameter($string, $startSlash, $variable, $regex, $optional)
    {
        $o = new \stdClass();
        $o->string = $string;
        $o->variable = $variable;
        $o->isRequired = $optional === '?' ? false : true;
        $o->regex = $this->makeParameterRegex($startSlash, $regex, $o->isRequired);
        $o->value = null;
        return $o;
    }

    /**
     * Transforms a catched parameter into a proper regex string
     * @param  string $startSlash
     * @param  string $regex
     * @param  bool $required
     * @return string
     */
    protected function makeParameterRegex($startSlash, $regex, $required)
    {
        $optional = !$required ? '?' : '';
        $startSlash = strlen($startSlash) ? '\/' . $optional : '';
        if(strlen($regex)) return $startSlash . '(' . trim($regex,'/') . ')' . $optional;
        return $startSlash . '(.[^\/]*)' . $optional;
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
     * Fills the parameters for given route
     * @return void
     */
    public function gatherParameters($route, $lang)
    {
        foreach ($this->extractParameters($route, $lang) as $i => $parameter) {
            $this->parameters[$i]->value = $parameter;
        }
    }

    /**
     * Retrieves the parameters for the given route.
     * @return array
     */
    public function extractParameters($route, $lang)
    {
        preg_match($this->regexen[$lang], $route, $matches);
        array_shift($matches);
        return array_map(function($parameter) {
            return urldecode($parameter);
        }, $matches);
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
