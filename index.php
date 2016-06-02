<?php
/**
 * Kabas - Build custom easy websites in a record time
 *
 * @package  Kabas
 * @author   WhiteCube <hello@whitecube.be>
 */
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
 setlocale(LC_TIME, 'fr_FR');
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
