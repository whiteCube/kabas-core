<?php 

namespace Kabas\Http\Routes;

use Kabas\Utils\Lang;

class UrlWorker {
    protected $subdirectory;

    public function __construct()
    {
        $this->subdirectory = $this->setSubdirectory();
    }

    /**
     * Retrieves the subdirectory the CMS may be in.
     * @return string
     */
    public function setSubdirectory()
    {
        preg_match('/(.+)?index.php$/', $_SERVER['SCRIPT_NAME'], $matches);
        if(strlen($matches[1]) > 1) return (substr($matches[1], 0, 1) == '/' ? '' : '/') . rtrim($matches[1], '/');
        return '';
    }

    /**
     * Returns useful information about given URL
     * @param  string $url
     * @return object
     */
    public function parseUrl($url)
    {
        $data = parse_url($url);
        $parsed = $this->buildParsedUrlObject($data);
        if(!isset($data['path'])) return $parsed;
        $parsed->base = $parsed->root;
        if($this->containsSubdirectory($data['path'])){
            $parsed->base .= $this->subdirectory;
            $parsed->query = $this->getQuery($data['path']);
        }
        else $parsed->query = $data['path'];
        $parsed->base .= '/';
        $query = $this->getCleanQuery($parsed->query);
        $parsed->route = $query->route;
        if($query->lang) $parsed->lang = $query->lang;
        return $parsed;
    }


    /**
     * Retrieves the path from URI
     * @param  string $uri
     * @return string
     */
    public function getQuery($uri)
    {
        if($length = strlen($this->subdirectory)) {
            $start = strpos($uri, $this->subdirectory);
            $uri = substr($uri, $start >= 0 ? $start + $length : 0);
        }
        $uri = trim(explode('?', $uri)[0],'/');
        if(!strlen($uri)) return '/';
        return '/' . $uri;
    }

    
    /**
     * Get the lang-cleared route
     * @return object
     */
    public function getCleanQuery($uri)
    {
        preg_match('/^\/([^\/]+)?/', $uri, $matches);
        $data = new \stdClass();
        $data->lang = null;
        $data->route = null;
        if(isset($matches[1]) && $lang = Lang::find($matches[1])) {
            $data->lang = $lang;
            $data->route = substr($uri, strlen($matches[0]));
        }
        else {
            $data->route = $uri;
        }
        return $data;
    }

    /**
     * Check if path contains subdirectory
     * @param string $path 
     * @return bool
     */
    protected function containsSubdirectory($path)
    {
        return strlen($this->subdirectory) && strpos($path, $this->subdirectory) === 0;
    }

    /**
     * Build an URL object with some data
     * @param array $data 
     * @return object
     */
    protected function buildParsedUrlObject($data)
    {
        $parsed = new \stdClass();
        $parsed->root = isset($data['scheme']) ? $data['scheme'] . '://' : '';
        $parsed->root .= isset($data['host']) ? $data['host'] : '';
        $parsed->root .= isset($data['port']) ? ':' . $data['port'] : '';
        $parsed->base = false;
        $parsed->query = false;
        $parsed->lang = false;
        $parsed->route = false;
        return $parsed;
    }

}