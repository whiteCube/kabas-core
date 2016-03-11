<?php

/*
|--------------------------------------------------------------------------
| Initialising a new Kabas instance
|--------------------------------------------------------------------------
|
| Using the main class
|
*/

$app = new Kabas\Kabas();

$app->boot();
$app->react();

return $app;
