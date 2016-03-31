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
      'driver' => 'Json',

      /**
       * The mysql configuration (in case you want to use Eloquent in your models)
       * @var array
       */
      'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'kabas',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
      ],

      'imageDriver' => 'GD',

      /**
       * Aliases for classes
       * @var array
       */
      'aliases' => [
            'Assets' => Kabas\Utils\Assets::class,
            'Benchmark' => Kabas\Utils\Benchmark::class,
            'Menu' => Kabas\Utils\Menu::class,
            'Meta' => Kabas\Utils\Meta::class,
            'Model' => Kabas\Utils\Model::class,
            'Page' => Kabas\Utils\Page::class,
            'Part' => Kabas\Utils\Part::class,
            'Url' => Kabas\Utils\Url::class,
      ],
];
