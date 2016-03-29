<?php Part::header(); ?>
<h2><?= $title ?></h2>
<p>
      <?= $intro ?>
</p>

<div>
      <h2>Links</h2>
      <a href="<?= $link1 ?>" title="<?= $link1->title ?>" <?= $link1->isLocal() ? '' : 'target="blank"' ?>><?= $link1->label ?></a>
</div>

<div>
      <h2>Images</h2>
      <img src="<?= $banner->fit(450, 300)->apply()->src() ?>" alt="<?= $banner->alt() ?>" />
      <img src="<?= $randomImage->fit(450, 300)->apply()->src() ?>" alt="<?= $randomImage->alt() ?>" />
</div>

<div>
      <h2>Checkboxes</h2>
      <?php foreach($faveFoods->all() as $checkbox): ?>
            <p>
                  <strong><?= $checkbox->isSelected() ? '●' : '○' ?> <?= $checkbox->label() ?></strong>
            </p>
      <?php endforeach; ?>

      <?php if($isAwesome->isSelected()): ?>
            <h2>Awesome mode activated!</h2>
            <img src="https://media.giphy.com/media/DpB9NBjny7jF1pd0yt2/giphy.gif" alt="Awesome mode" />
      <?php endif; ?>
</div>



<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
