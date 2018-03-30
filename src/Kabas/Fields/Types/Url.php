<?php

namespace Kabas\Fields\Types;

use Kabas\App;
use Kabas\Fields\Textual;
use Kabas\Utils\Url as UrlUtil;

class Url extends Textual
{

    protected $target;

    /**
     * Condition to check if the value is correct for this field type.
     * @param  mixed $value
     * @return bool
     */
    public function condition()
    {
        return is_string($this->value);
    }

    /**
     * Check if url is from this domain
     * @return boolean
     */
    public function isLocal()
    {
        if($this->target) return true;
        if(trim(UrlUtil::parse($this->value)->base, '/') == UrlUtil::base()) return true;
        return false;
    }

    /**
     * Get the parsed url
     * @return object
     */
    public function getParsed()
    {
        return UrlUtil::parse($this->output);
    }

    /**
     * Checks if this URL has a defined internal target
     * @return boolean
     */
    public function hasTarget()
    {
        return is_null($this->target) ? false : true;
    }

    /**
     * Get the Url's target object if defined
     * @return object
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Makes an output URL from value (without trailing slashes)
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        if(!is_string($value) || !strlen($value) || !($parts = $this->getValueParts($value))) return '';
        if($page = $this->findTarget($parts->identifier)){
            $this->target = $page;
            return $this->makeUrlFromPageRoute($parts);
        }
        elseif(filter_var($parts->identifier, FILTER_VALIDATE_URL)){
            return $this->makeUrlFromParsedUrl($parts);
        }
        return '';
    }

    /**
     * Parses given raw value and retrieves its different component parts
     * @param  string $value
     * @return object
     */
    protected function getValueParts($value)
    {
        if(!preg_match('/^(.*?)(?:\((.+?)\))?(?:\[(.+?)\])?(?:\#(.+))?$/', $value, $matches)) return;
        $parts = new \stdClass();
        $parts->identifier = (isset($matches[1]) && strlen($matches[1])) ? trim($matches[1]) : null;
        $parts->parameters = (isset($matches[2]) && strlen($matches[2])) ? $this->getValueParameters($matches[2]) : null;
        $parts->lang = (isset($matches[3]) && strlen($matches[3])) ? trim($matches[3]) : null;
        $parts->anchor = (isset($matches[4]) && strlen($matches[4])) ? trim($matches[4]) : null;
        return $parts;
    }

    /**
     * Parses given raw parameters value
     * @param  string $value
     * @return array
     */
    protected function getValueParameters($value)
    {
        return array_map(function($param) {
            return trim($param);
        }, explode(',',$value));
    }

    /**
     * Returns target page if exists
     * @param  string $id
     * @return object
     */
    protected function findTarget($id)
    {
        return App::content()->pages->get($id);
    }

    /**
     * Creates an URL from given value parts containing a page id and optional parameters
     * @param  object $parts
     * @return string
     */
    protected function makeUrlFromPageRoute($parts)
    {
        $url = UrlUtil::to($parts->identifier, $this->getPageParameters($parts->identifier, $parts->parameters), $parts->lang);
        return $url . ($parts->anchor ? '#' . $parts->anchor : '');
    }

    /**
     * Fills a parameter array for given page route and parameters list
     * @param  string $id
     * @param  array $parameters
     * @return string
     */
    protected function getPageParameters($id, $parameters)
    {
        if(!$id || !$parameters) return [];
        $filled = [];
        foreach (App::router()->getRouteByPage($id)->parameters as $i => $param) {
            if(!isset($parameters[$i])) continue;
            $filled[$param->variable] = $parameters[$i];
        }
        return $filled;
    }

    /**
     * Recomposes an URL from parsed URL parts
     * @param  object $parts
     * @return string
     */
    protected function makeUrlFromParsedUrl($parts)
    {
        return trim($parts->identifier, '/') . ($parts->anchor ? '#' . $parts->anchor : '');
    }

}
