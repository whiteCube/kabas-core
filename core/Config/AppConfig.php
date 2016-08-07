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
       * The default driver used to get your site's data in the config and content
       * folders. Only JSON is supported at the moment.
       * @var string
       */
      'driver' => 'json',

      /**
       * The driver used by Intervention Image (GD | imagick)
       */
      'imageDriver' => 'GD',

      /**
       * Aliases for classes
       * @var array
       */
      'aliases' => [
            'Assets' => Kabas\Utils\Assets::class,
            'Benchmark' => Kabas\Utils\Benchmark::class,
            'Lang' => Kabas\Utils\Lang::class,
            'Menu' => Kabas\Utils\Menu::class,
            'Meta' => Kabas\Utils\Meta::class,
            'Page' => Kabas\Utils\Page::class,
            'Part' => Kabas\Utils\Part::class,
            'Session' => Kabas\Utils\Session::class,
            'Text' => Kabas\Utils\Text::class,
            'Url' => Kabas\Utils\Url::class
      ],
];
