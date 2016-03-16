<ul class="menu">
      <?php foreach($menu as $menuItem): ?>
            <li>
                  <a href="<?= $menuItem->url ?>" style="color: <?= $options->color; ?>"><?= $menuItem->label ?></a>
            </li>
      <?php endforeach; ?>
</ul>
