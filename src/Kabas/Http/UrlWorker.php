<?php 

namespace Kabas\Http;

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
    protected function setSubdirectory()
    {
        preg_match('/(.+)?index.php$/', $_SERVER['SCRIPT_NAME'], $a);
        if(strlen($a[1]) > 1) return (substr($a[1], 0, 1) == '/' ? '' : '/') . rtrim($a[1], '/');
        return '';
    }

    /**
     * Returns useful information about given URL
     * @param  string $url
     * @return object
     */
    public function parseUrl($url)
    {
        $a = parse_url($url);
        $o = new \stdClass();
        $o->root = isset($a['scheme']) ? $a['scheme'] . '://' : '';
        $o->root .= isset($a['host']) ? $a['host'] : '';
        $o->root .= isset($a['port']) ? ':' . $a['port'] : '';
        $o->base = false;
        $o->query = false;
        $o->lang = false;
        $o->route = false;
        if(isset($a['path'])){
            $o->base = $o->root;
            if(strlen($this->subdirectory) && strpos($a['path'], $this->subdirectory) === 0){
                $o->base .= $this->subdirectory;
                $o->query = $this->getQuery($a['path']);
            }
            else $o->query = $a['path'];
            $o->base .= '/';
            $q = $this->getCleanQuery($o->query);
            $o->route = $q->route;
            if($q->lang) $o->lang = $q->lang;
        }
        return $o;
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
        return '/' . $uri . '/';
    }

    
    /**
     * Get the lang-cleared route
     * @return object
     */
    public function getCleanQuery($uri)
    {
        preg_match('/^\/([^\/]+)?/', $uri, $a);
        $o = new \stdClass();
        $o->lang = null;
        $o->route = null;
        if(isset($a[1]) && $lang = Lang::find($a[1])){
            $o->lang = $lang;
            $o->route = substr($uri, strlen($a[0]));
        }
        else{
            $o->route = $uri;
        }
        return $o;
    }

}