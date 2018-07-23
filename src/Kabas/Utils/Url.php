<?php

namespace Kabas\Utils;

use Kabas\App;
use Kabas\Http\Routes\Route;
use Kabas\Utils\Lang;
use Kabas\Exceptions\NotFoundException;
use Kabas\Exceptions\ArgumentMissingException;
use Kabas\Http\Request\Query;

class Url
{
    /**
     * Get the URI to the desired page
     * @param  string $id
     * @param  array $params (optionnal)
     * @param  mixed $lang (optionnal)
     * @return string
     */
    static function to(string $id, array $params = [], $lang = null)
    {
        $route = App::router()->getRouteByPage($id);
        if (!$route) throw new NotFoundException($id);
        return self::generate($route, $params, $lang);
    }

    /**
     * Get Url to current page
     * @return string
     */
    static function getCurrent()
    {
        $route = App::router()->getCurrent();
        return self::generate($route, $route->getParameters());
    }

    /**
     * Generate an URL to the current page in another language.
     * @param  mixed $lang
     * @return string
     */
    static function lang($lang)
    {
        $route = App::router()->getCurrent();
        return self::generate($route, $route->getParameters(), $lang);
    }

    /**
     * Get the base url of the site
     * @return string
     */
    static function base()
    {
        return App::request()->getQuery()->getBase();
    }

    /**
     * Get the URL to given public asset in active theme
     * @return string
     */
    static function asset($path)
    {
        return Assets::src($path);
    }

    /**
     * Get the URL for given path
     * @param  string $path
     * @return string
     */
    static function fromPath($path)
    {
        if(strpos($path, PUBLIC_PATH) !== 0) return false;
        $path = trim(str_replace(DS, '/', substr($path, strlen(PUBLIC_PATH))), '/');
        return self::base() . '/' . $path;
    }

    /**
     * Returns Kabas-parsed URL
     * @param  string $url
     * @return Kabas\Http\Request\Query
     */
    static function parse($url)
    {
        // TODO : do this in a static creator
        $url = parse_url($url);
        $query = new Query(App::config()->languages, $url['host'], $url['path'] ?? '/', $_SERVER['SCRIPT_NAME'], (($url['scheme'] ?? '') === 'https'));
        return $query;
    }

    /**
     * Returns route found in URL
     * @param  string $url
     * @return object
     */
    static function route($url)
    {
        // TODO : do this in a static creator
        $url = parse_url($url);
        $query = new Query(App::config()->languages, $url['host'], $url['path'] ?? '/', $_SERVER['SCRIPT_NAME'], (($url['scheme'] ?? '') === 'https'));
        return $query->getRoute();
    }

    /**
     * Returns an absolute URL for the given route
     * @param  Kabas\Http\Routes\Route $route
     * @param  array $params
     * @param  mixed $lang
     * @return string
     */
    protected static function generate(Route $route, array $params = [], $lang = null)
    {
        $lang = Lang::getOrDefault($lang);
        $url = [self::base()];
        if(!$lang->isDefault || !App::config()->get('lang.hideDefault')){
            $url[] = $lang->slug;
        }
        $url[] = self::fillRouteWithParams($route, $params, $lang);
        return rtrim(implode('/', $url), '/');
    }

    /**
     * Returns an absolute URL for the given route
     * @param  Kabas\Http\Routes\Route $route
     * @param  array $params
     * @param  Kabas\Config\Language $lang
     * @return string
     */
    protected static function fillRouteWithParams(Route $route, $params, $lang)
    {
        if(!($str = $route->getDefinition($lang->original))) return;
        foreach($route->parameters as $parameter){
            if($parameter->isRequired && !array_key_exists($parameter->variable, $params)){
                throw new ArgumentMissingException('route', 'required parameter "' . $parameter->variable . '" is undefined');
            } else if(array_key_exists($parameter->variable, $params)) {
                $str = str_replace(ltrim($parameter->string, '/'), $params[$parameter->variable], $str);
            } else {
                $str = str_replace(ltrim($parameter->string, '/'), '', $str);
            }
        }
        return trim($str, '/');
    }
}
