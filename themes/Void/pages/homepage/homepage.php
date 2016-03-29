<?php Part::header(); ?>
<h2><?= $title ?></h2>
<p>
      <?= $intro ?>
</p>

<div>
      <h2>Images</h2>
      <a href="<?= $banner->src() ?>">
      <img src="<?= $banner->fit(450, 300)->apply()->src() ?>" alt="" />
      </a>
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
