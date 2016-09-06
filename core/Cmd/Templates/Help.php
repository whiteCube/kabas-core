<?php
echo "


      ##  ##(          /#/         ######          /#/         ######/
      #####           /###         ##, (#*        /###,        ##.
      #####          /## ##.       ######/       /## ##.        (####
      ##  ##        /##   ##.           ##      /##   ##.          .##
      ##   ##*     /##     ##.        ###*     /##     ##.    .######.



      Kabas Help

      Available commands:

      make:theme \$name                         # Creates a complete all directories for a theme.
      make:template \$name                      # Creates a template's structure, controller and view files in the active theme.
      make:partial \$name                       # Creates a partial's structure, controller and view files in the active theme.
      make:menu \$name                          # Creates a menu's structure, controller and view files in the active theme.
      make:model \$name \$driver                # Creates a new model (structure & class) in the active theme, with driver 'eloquent' or 'json'.
      content:page \$id ...\$langs              # Generates a content page, langs are optional (defaults to all).
      content:partial \$id ...\$langs           # Generates a content partial, langs are optional (defaults to all).
      content:menu \$id ...\$langs              # Generates a content menu, langs are optional (defaults to all).
      content:object \$model ...\$langs         # Generates an object file for the given model, langs are optional (defaults to all), id is automatically generated. This command is only useful if the given model uses the JSON driver.
";
