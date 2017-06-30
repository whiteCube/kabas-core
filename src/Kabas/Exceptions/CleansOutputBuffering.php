<?php 

namespace Kabas\Exceptions;

use Kabas\App;

trait CleansOutputBuffering {

    protected function clean()
    {
        if(!App::config()->get('app.debug')) ob_clean();
    }

}