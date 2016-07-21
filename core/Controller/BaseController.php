<?php

namespace Kabas\Controller;

use Kabas\App;
use Kabas\Utils\Url;
use Kabas\View\View;

class BaseController
{
      protected $item;

      protected $template = false;

      public function __construct($view)
      {
            $this->item = $view;
            $params = App::router()->getCurrent()->getParameters();
            $response = call_user_func_array([$this, 'setup'], $params);
            if(is_null($response)) {
                  $response = $this->view($this->getTemplateName(), false);
            }
            App::response()->send($response);
      }

      public function __call($method, $args)
      {
            return false;
      }

      public function redirect($pageID, $params = [], $lang = null)
      {
            return App::getInstance()->make('Kabas\Http\Responses\Redirect', [$pageID, $params, $lang]);
      }

      public function view($view, $data)
      {
            $this->item->build($data);
            return App::getInstance()->make('Kabas\Http\Responses\View', [$view, $this->item]);
      }

      public function json($data)
      {
            return App::getInstance()->make('Kabas\Http\Responses\Json', [$data]);
      }

      /**
       * Get the template filename
       * @return string
       */
      protected function getTemplateName()
      {
            if($this->template) return $this->template;
            return $this->item->template;
      }

}
