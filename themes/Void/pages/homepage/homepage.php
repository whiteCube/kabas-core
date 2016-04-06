<?php Part::header(); ?>

<h2><?= $title ?></h2>
<p>
      <?= $intro ?>
</p>

<?php Part::get('colors'); ?>
<?php Part::get('links'); ?>
<?php Part::get('images'); ?>
<?php Part::get('checkboxes'); ?>
<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
