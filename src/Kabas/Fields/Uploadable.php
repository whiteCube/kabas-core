<?php 

namespace Kabas\Fields;

use Kabas\App;

class Uploadable extends Item {

    protected $upload;

    protected function implement($structure)
    {
        if(App::uploads()->has($this->name)) {
            $this->upload = App::uploads()->get($this->name);
        }
        parent::implement($structure);
    }

}