<head>
      <title><?= Page::title() ?></title>
      <meta name="description" content="<?= Meta::get('description') ?>">
      <meta name="keywords" content="<?= Meta::get('keywords') ?>">
      <meta name="og:image" content="<?= Meta::get('og:image') ?>">
      <?php Assets::here('header'); ?>
      <?php Assets::add('header', 'main.css'); ?>
</head>
<div class="header">
      <h1><?= $title ?></h1>
      <h2><?= $baseline ?></h2>
</div>
<a href="<?= Url::lang('fr-FR'); ?>">Français</a>
<a href="<?= Url::lang('en-US'); ?>">English</a>
<?php Menu::get('main'); ?>
