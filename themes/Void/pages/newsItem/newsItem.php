<?php Part::header(); ?>

<h2><?= $news->title ?></h2>
<p>
      <?= $news->created_at->formatLocalized('%A %d %B %Y') ?> (<?= $news->created_at->diffForHumans() ?>)
</p>
<img src="<?= $news->image->fit(450, 300)->apply()->src() ?>" alt="" />
<p>
      <?= $news->content ?>
</p>

<?php Part::footer(); ?>
