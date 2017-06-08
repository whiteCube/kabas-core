<?php

use Kabas\App;

/**
 * Kabas application (website) launch
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

require_once __DIR__ . '/../../../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Let's fly
|--------------------------------------------------------------------------
|
| Initialise a new Kabas instance and run the app.
|
*/

$kabas = new App(realpath(__DIR__));
$kabas->boot();
