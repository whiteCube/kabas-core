<nav class="next">
      <h2 class="next__title"><?= $title;?></h2>
      <div class="next__content"><?= $content;?></div>
      <?php foreach($items as $item):?>
            <a href="<?= $item->url;?>" class="next__item<?= ($item->icon ? ' next__item--' . $item->icon : '') . ($item->isActive() ? ' next__item--active' : '');?>">
                  <span class="next__label"><?= $item->label;?></span>
            </a>
      <?php endforeach;?>
</nav>