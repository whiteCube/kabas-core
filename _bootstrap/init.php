<?php

/*
|--------------------------------------------------------------------------
| Initialising a new Kabas instance
|--------------------------------------------------------------------------
|
| Using the main class
|
*/

$app = new Kabas\App();


/*
|--------------------------------------------------------------------------
| Loading everything we need
|--------------------------------------------------------------------------
|
| Once everything's loaded, react to the request.
|
*/
$app->boot();
$app->loadAliases();
$app->loadTheme();
$app->react();

return $app;
