<?php Part::header(); ?>

<h2><?= $title ?></h2>
<p>
      <?= $intro->baseHeading(5) ?>
</p>

<?php Part::get('news'); ?>
<?php Part::get('dates'); ?>
<?php Part::get('colors'); ?>
<?php Part::get('links'); ?>
<?php Part::get('images'); ?>
<?php Part::get('checkboxes'); ?>
<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
