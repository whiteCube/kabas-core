# Theme folder

This folder contains all the files Kabas will use in order to display the website. If you want to build you own theme, this is the place where you'll spend the most of your time.

Please consider the following document architecture:

- `/models/`: Folder where all data relations are defined
- `/controllers/`: Folder containing PHP classes that will format and perform the needed logic in order to display beautiful pages. This directory is sub-divided into 3 other folders:
      - `/menus/`: Navigation controllers
      - `/partials/`: Parts that appear on multiple templates can have their own logic here
      - `/templates/`: Page Controllers
- `/views/`: Folder containing all HTML templates, directly linked to their controllers:
      - `/menus/`: Markup for each navigation menu
      - `/partials/`: Markup for each template-part
      - `/templates/`: Markup for whole pages (using the partials above)
- `/structures/`: This is where Kabas will search for JSON files defining the variables used in controllers, models and views:
      - `/menus/`: Defines the fields used in navigation menus
      - `/models/`: Defines a _migration_ structure used in models
      - `/partials/`: Defines the fields used in partials
      - `/templates/`: Defines the fields used in pages but not yet defined in menus or partials
- `/public/`: Folder containing the publicly queryable assets. You can structure the content of this folder as you want, but here is how we do it:
      - `/css/`
      - `/js/`
      - `/img/`
      - `/font/`