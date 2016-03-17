<?php Assets::load('header'); ?>
<?php Part::header(); ?>
<h2><?= $title; ?></h2>
<p>
      <?= $intro; ?>
</p>

<?php Assets::required('header', 'css file here'); ?>
<?php Assets::required('footer', 'js file here'); ?>

<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
