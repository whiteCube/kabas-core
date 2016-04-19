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
            <p class='message'><?= $message ?></p>
            <?php if(isset($lint)): ?>
                  <pre class='lint'><?= $lint ?></pre>
            <?php endif; ?>
            <?php if(isset($hint)): ?>
                  <div class="hint"><?= $hint ?></div>
            <?php endif; ?>
      </body>
</html>
