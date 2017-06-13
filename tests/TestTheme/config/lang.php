<?php
return [

      /**
       * The languages available on the website.
       * Each available language must have its own folder inside the content folder.
       */
      'available' => [
            'en-GB' => ['slug' => 'en', 'label' => 'English'],
            'fr-FR' => ['slug' => 'fr', 'label' => 'Français']
      ],

      /**
       * Language used when the user's choice is undefined
       */
      'default' => 'en-GB',
];
