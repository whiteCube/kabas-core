<?php

namespace Kabas\Controller;

use Kabas\App;
use Kabas\Utils\Url;
use Kabas\View\View;

class BaseController
{
    protected $item;

    static $template = false;

    public function __construct($item)
    {
        $this->item = $item;
        $params = App::router()->getCurrent()->getParameters();
        $response = call_user_func_array([$this, 'setup'], $params);
        if(is_null($response)) {
            $response = $this->view($this->getTemplateName(), false);
        }
        App::response()->send($response);
    }

    public function __get($key)
    {
        return $this->item->$key;
    }

    public function __set($key, $value)
    {
        $this->item->$key = $value;
    }

    public function __call($method, $args)
    {
        if($method == 'setup') return null;
        return false;
    }

    public function redirect($pageID, $params = [], $lang = null)
    {
        return new \Kabas\Http\Responses\Redirect($pageID, $params, $lang);
    }

    public function view($view, $data)
    {
        $this->item->set($data);
        return new \Kabas\Http\Responses\View($view, $this->item);
    }

    public function json($data)
    {
        return new \Kabas\Http\Responses\Json($data);
    }

    /**
     * Get the template filename
     * @return string
     */
    protected function getTemplateName()
    {
        if(self::$template) return self::$template;
        return $this->item->template;
    }

}
