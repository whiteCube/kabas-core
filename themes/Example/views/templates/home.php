<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <title>Test</title>
      <?php Assets::here('header', 'css/example.css'); ?>
</head>
<body><div class="intro">
      <header class="intro__head">
            <h1 class="intro__logo">Kabas</h1>
            <p class="intro__baseline">Thought for humans, built for developers.</p>
      </header>
      <main class="intro__content">
            <section class="welcome">
                  <h2 class="welcome__title"><?= $title;?></h2>
                  <div class="welcome__content"><?= $text->baseHeading(3);?></div>
            </section>
            <nav class="next">
                  <h2 class="next__title">What's next?</h2>
                  <p>Let us help you during the first steps on this Kabas website.</p>
                  <a href="#" class="next__item">Test</a>
            </nav>
      </main>
      <footer class="intro__end">
            <p class="intro__version">version <?= \Kabas\App::VERSION ?></p>
      </footer>
</div></body>
</html>