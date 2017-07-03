<?php 

namespace Kabas\Exceptions;

use Kabas\App;

trait CleansOutputBuffering {

    /**
     * Deletes the current output buffer if debug mode is disabled
     * This is to make sure your users don't end up with 
     * a half-loaded broken page if an error occurs.
     * @return void
     */
    protected function clean()
    {
        if(!App::config()->get('app.debug') && ob_get_level()) ob_clean();
    }

}