<div>
      <h2><?= $title ?></h2>
      <?php foreach($news as $news_article): ?>
            <article>
                  <h3><a href="/news/view/<?= $news_article->id ?>"><?= $news_article->title->uppercase() ?></a></h3>
                  <p><?= $news_article->content ?></p>
                  <img src="<?= $news_article->image->fit(450, 300)->apply()->src() ?>" alt="<?= $news_article->image->alt() ?>" />
            </article>
      <?php endforeach; ?>
</div>
