<?php
/**
 * Whoops - php errors for cool kids
 * @author Filipe Dobreira <http://github.com/filp>
 */

namespace Kabas\Exceptions\Whoops;

use \Whoops\Handler\PrettyPageHandler;

class KabasPrettyPageHandler extends PrettyPageHandler
{

    public function handle()
    {
        $this->addResourcePath(__DIR__);
        $this->addCustomCss('CustomWhoopsStyles.css');
        parent::handle();
    }

}
