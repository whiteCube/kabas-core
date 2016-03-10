<?php

namespace Kabas;

class Kabas
{
    /**
     * The current Kabas version
     *
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * Instanciate the application
     *
     * @return void
     */
    public function __construct()
    {
          $this->ConfigContainer = new \Kabas\Config\Container();
    }


    /**
     * Get the version number of this Kabas website.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

}
