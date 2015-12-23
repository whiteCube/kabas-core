<?php

/**
 * Kabas - Build custom easy websites in a record time
 *
 * @package  Kabas
 * @author   WhiteCube <hello@whitecube.be>
 */


/*
|--------------------------------------------------------------------------
| Start point
|--------------------------------------------------------------------------
|
| Every request is sent through this file, where the core system is
| included and executed.
|
*/

require __DIR__.'/core/_bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Let's fly
|--------------------------------------------------------------------------
|
| Initialise a new Kabas instance
|
*/

$kabas = require_once __DIR__.'/core/_bootstrap/init.php';