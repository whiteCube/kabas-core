<div class="links">
      <h2>Links</h2>
      <a href="<?= $link1 ?>" title="<?= $link1->title ?>" <?= $link1->isLocal() ? '' : 'target="blank"' ?>><?= $link1->label ?></a>
      <p>
            Parsing:
      </p>
      <?php var_dump($link1->parse()); ?>
</div>
