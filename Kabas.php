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
     * Get the version number of this Kabas website.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }
    
}