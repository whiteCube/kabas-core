<?php
echo "


      ##  ##(          /#/         ######          /#/         ######/
      #####           /###         ##, (#*        /###,        ##.
      #####          /## ##.       ######/       /## ##.        (####
      ##  ##        /##   ##.           ##      /##   ##.          .##
      ##   ##*     /##     ##.        ###*     /##     ##.    .######.



      Kabas Help

      Available commands:

      \033[32mmake:theme \033[0m\$theme                   # Create directory structure for a theme.
      \033[32mmake:page \033[0m\$page                     # Create page structure in the active theme.
      \033[32mmake:part \033[0m\$part                     # Create part structure in the active theme.
      \033[32mmake:menu \033[0m\$menu                     # Create menu structure in the active theme.
      \033[32mmake:model \033[0m\$model \$driver           # Create model structure in the active theme, with driver 'eloquent' or 'json'.
      \033[32mcontent:page \033[0m\$page ...\$langs        # Create content file for your page, langs are optional (defaults to all).
      \033[32mcontent:part \033[0m\$part ...\$langs        # Create content file for your part, langs are optional (defaults to all).
      \033[32mcontent:menu \033[0m\$menu ...\$langs        # Create content file for your menu, langs are optional (defaults to all).
";
