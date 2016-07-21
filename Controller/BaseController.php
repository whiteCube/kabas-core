<?php

namespace Kabas\Controller;

use Kabas\App;
use Kabas\Utils\Url;
use Kabas\View\View;

class BaseController
{
      protected $viewName;
      protected $config;
      protected $fnInit = 'setup';

      public function __construct($view)
      {
        var_dump($view);die();
            $this->defaultTemplateName = $view->template;
            $this->viewID = $view->id;
            $this->data = isset($view->data) ? $view->data : new \stdClass;
            $this->options = isset($view->options) ? $view->options : null;
            $this->meta = isset($view->meta) ? $view->meta : null;
            $params = App::router()->getCurrent()->getParameters();
            $response = call_user_func_array([$this, $this->fnInit], $params);
            $this->checkLinkedFiles();
            if(is_null($response)) {
                  $response = $this->view($this->viewName, $this->data);
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
            $data = $this->constructViewData($data);
            return App::getInstance()->make('Kabas\Http\Responses\View', [$view, $data, $this->directory]);
      }

      public function json($data)
      {
            return App::getInstance()->make('Kabas\Http\Responses\Json', [$data]);
      }

      /**
       * Get the data from $this and format it into an object
       * that can be passed to the view.
       * @return stdClass
       */
      protected function constructViewData($data)
      {
            App::content()->pages->getCurrent()->loadFields();
            $data->options = $this->options;
            $data->meta = $this->meta;
            return $data;
      }

      /**
       * Check if the theme provided a custom template filename
       * @return void
       */
      protected function checkLinkedFiles()
      {
            if(!$this->viewName) $this->viewName = $this->guessViewFile();
      }

      /**
       * Get the default template filename
       * @return string
       */
      protected function guessViewFile()
      {
            return $this->defaultTemplateName;
      }

}
