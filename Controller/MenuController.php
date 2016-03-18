<?php

namespace Kabas\Controller;

use Kabas\View\View;

class MenuController
{
      protected $view;
      protected $config;

      public function __construct($template, $menuItems, $options = null)
      {
            $this->defaultTemplateName = $template;
            $this->checkLinkedFiles();

            $this->items = $this->instanciateMenuItems($menuItems);
            $this->options = $options;
            $this->setup();

            $this->render($this->constructViewData());
      }

      /**
       * In this function you get the chance to process the data inside
       * $this->items and $this->options before it is passed on to the
       * view. Please re-declare it in your menu's controller.
       * @return void
       */
      protected function setup()
      {
      }

      /**
       * Create a MenuItem instance for each menu item declared in
       * the content json file.
       * @param  stdClass $menuItems
       * @return void
       */
      protected function instanciateMenuItems($menuItems)
      {
            $items = [];
            foreach($menuItems as $itemID => $itemData) {
                  $items[$itemID] = new MenuItem($itemID, $itemData);
                  if(isset($items[$itemID]->subitems)) {
                        $items[$itemID]->subitems = $this->instanciateMenuItems($items[$itemID]->subitems);
                  }
            }

            return $items;
      }

      /**
       * Get the data from $this and format it into an object
       * that can be passed to the view.
       * @return stdClass
       */
      protected function constructViewData()
      {
            $data = new \stdClass();
            $data->menu = $this->items;
            $data->options = $this->options;

            return $data;
      }

      /**
       * Render the current menu onto the page.
       * @param  stdClass $data
       * @return void
       */
      protected function render($data)
      {
            View::make($this->view, $data);
      }

      /**
       * Check if the theme provided a custom template or config filename
       * @return void
       */
      protected function checkLinkedFiles()
      {
            if(!$this->view) $this->view = $this->guessViewFile();
            if(!$this->config) $this->config = $this->guessConfigFile();
      }

      /**
       * Get the default template filename
       * @return string
       */
      protected function guessViewFile()
      {
            return $this->defaultTemplateName;
      }

      /**
       * Get the default config filename
       * @return string
       */
      protected function guessConfigFile()
      {

      }

}
