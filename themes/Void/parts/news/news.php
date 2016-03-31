<div>
      <h2><?= $title ?></h2>
      <?php foreach($news as $news_article): ?>
            <article>
                  <h3><?= $news_article->title->uppercase() ?></h3>
                  <p><?= $news_article->content ?></p>
            </article>
      <?php endforeach; ?>
</div>
