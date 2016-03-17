<div class="section1">
      <h2 style="color: <?= $options->title->color; ?>;"><?= $title; ?></h2>
      <p>
            <?= $text; ?>
      </p>
</div>
<?php Assets::required('footer', 'section1.js'); ?>
