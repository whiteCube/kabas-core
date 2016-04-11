<?php Part::header(); ?>

<h2><?= $title ?></h2>
<p>
      <?= $description ?>
</p>
<?php foreach($news as $newsItem): ?>
<article style="display: inline-block; width: 30%; margin-right: 2%;">
      <h3><a href="<?= Url::to('newsItem', $newsItem->slug) ?>"><?= $newsItem->title ?></a></h3>
      <img src="<?= $newsItem->image->fit(450, 300)->apply()->src() ?>" alt="<?= $newsItem->image->alt() ?>" />
      <p><?= $newsItem->content ?></p>
</article>
<?php endforeach; ?>

<?php Part::footer(); ?>
