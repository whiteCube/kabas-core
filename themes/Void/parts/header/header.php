<style>
      body {
            font-family: 'Avenir Next', sans-serif;
            color: rgb(44, 45, 54);
            padding: 3em;
      }
</style>
<?php Assets::here('header'); ?>
<div class="header">
      <h1><?= $title ?></h1>
      <h2><?= $baseline ?></h2>
</div>

<?php Menu::get('main'); ?>
