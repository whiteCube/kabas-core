<?php Part::header(); ?>

<h2><?= $news->title ?></h2>
<img src="<?= $news->image->fit(450, 300)->apply()->src() ?>" alt="" />
<p>
      <?= $news->content ?>
</p>

<?php Part::footer(); ?>
