<div>
      <h2><?= $title ?></h2>
      <?php foreach($news as $news_article): ?>
            <article>
                  <h3><a href="/news/<?= $news_article->slug ?>"><?= $news_article->title->uppercase() ?></a></h3>
                  <p>
                        Publié <?= $news_article->created_at->formatLocalized('%A %d %B %Y') ?> (<?= $news_article->created_at->diffForHumans() ?>)
                  </p>
                  <small>
                        Mis à jour <?= $news_article->updated_at->diffForHumans() ?>
                  </small>
                  <p><?= $news_article->content ?></p>
                  <img src="<?= $news_article->image->fit(450, 300)->apply()->src() ?>" alt="<?= $news_article->image->alt() ?>" />
            </article>
      <?php endforeach; ?>
</div>
