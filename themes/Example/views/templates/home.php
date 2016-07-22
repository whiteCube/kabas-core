<?php Part::head();?>
<div class="intro">
      <?php Part::header();?>
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
      <?php Part::footer();?>
</div>
<?php Part::foot();?>