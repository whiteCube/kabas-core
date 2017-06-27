<?php
/**
 * Whoops - php errors for cool kids
 * @author Filipe Dobreira <http://github.com/filp>
 */

namespace Kabas\Exceptions;

use \Whoops\Handler\PrettyPageHandler;

class KabasPrettyPageHandler extends PrettyPageHandler
{

    public function handle()
    {
        parent::handle();
    }

}
