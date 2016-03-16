<?php

/*
|--------------------------------------------------------------------------
| Application configuration
|--------------------------------------------------------------------------
|
| Here is defined the core application configuration such as
| what type of driver is used to access data (ex JSON, mysql, etc).
|
*/

return [

      /**
       * The driver used to get your site's data in the config and content
       * folders. Only JSON is supported at the moment.
       *
       * @var string
       */
      'driver' => 'JSON',

      /**
       * Aliases for classes
       *
       * @var array
       */
      'aliases' => [
            'Url' => Kabas\Utils\Url::class,
            'Menu' => Kabas\Utils\Menu::class,
            'Part' => Kabas\Utils\Part::class,
      ],
];
