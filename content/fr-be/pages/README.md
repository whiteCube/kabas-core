# Pages content & architecture

This directory contains the files and the route definitions for every page the website will use.

Only **JSON files** will be used by Kabas to create the wanted page.

You can name your files as you want, since the route is defined inside each file. Pay attention to use only simple characters in your file names (and no spaces).

*Example file : home.json (or index.json/accueil.json/...)*

```
{
      "route": "/",
      "id": "home",
      "template": "homepage"
}
```

*Example file : contact.json (or [whatever-you-want.json])*

```
{
      "route": "/about/contact",
      "id": "contact",
      "template": "contact"
}
```

Good practice would be to create sub-folders named as the route segments in order to create a maintainable website. E.g. : your file `contact.json` of the above example should be stored in the folder `./content/pages/about/contact.json`.