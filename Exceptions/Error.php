<!DOCTYPE html>
<html>
      <head>
            <meta charset="utf-8">
            <title><?= $title ?></title>
            <style media="screen">
                  body {
                        padding: 1.3em 3em;
                        font-family: 'Avenir Next', sans-serif;
                  }
            </style>
      </head>
      <body>
            <h1>Kabas Error</h1>
            <h2><?= $type ?></h2>
            <p><?= $message ?></p>
            <?php if(isset($lint)): ?>
                  <pre><?= $lint ?></pre>
            <?php endif; ?>
            <?php if(isset($hint)): ?>
                  <p><?= $hint ?></p>
            <?php endif; ?>
      </body>
</html>
