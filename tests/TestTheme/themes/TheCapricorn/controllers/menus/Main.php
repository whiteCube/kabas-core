<?php 

namespace Theme\TheCapricorn\Menus;

use Kabas\Controller\MenuController;

class MainNav extends MenuController {

    public function setup()
    {
        $this->items->remove(1);
    }

}