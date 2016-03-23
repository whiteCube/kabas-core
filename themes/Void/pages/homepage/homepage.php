<?php Part::header(); ?>
<h2><?= $title ?></h2>
<p>
      <?= $intro ?>
</p>

<?php foreach($faveFoods->all() as $checkbox): ?>
      <p>
            <strong><?= $checkbox->label() ?>:</strong>
            <?= $checkbox->isSelected() ?>
      </p>
<?php endforeach; ?>

<?php if($isAwesome->isSelected()): ?>
      <p>Awesome mode activated!</p>
      <img src="https://media.giphy.com/media/DpB9NBjny7jF1pd0yt2/giphy.gif" alt="Awesome mode" />
<?php endif; ?>

<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
