<?php Part::head();?>
<div class="intro">
      <?php Part::header();?>
      <main class="intro__content">
            <section class="welcome">
                  <h2 class="welcome__title"><?= $title;?></h2>
                  <div class="welcome__content"><?= $text->baseHeading(3);?></div>
            </section>
            <?php Menu::main();?>
      </main>
      <?php Part::footer();?>
</div>
<?php Part::foot();?>