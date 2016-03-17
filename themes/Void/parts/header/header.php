<head>
      <?php Assets::here('header'); ?>
      <?php Assets::required('header', 'main.css'); ?>
</head>
<div class="header">
      <h1><?= $title ?></h1>
      <h2><?= $baseline ?></h2>
</div>

<?php Menu::get('main'); ?>
