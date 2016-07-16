<ul class="menu">
      <?php foreach($menu as $item): ?>
            <li class="menu__item">
                  <a href="<?= $item->url ?>" class="menu__link"><?= $item->label ?></a>

                  <?php if($item->hasSubItems()): ?>
                  <ul class="menu__sub">
                        <?php foreach($item->items as $sub): ?>
                        <li class="menu__item">
                              <a href="<?= $sub->url ?>" class="menu__link"><?= $sub->label ?></a>

                              <?php if($sub->hasSubItems()): ?>
                                    <ul class="menu__sub">
                                          <?php foreach($sub->items as $sItem): ?>
                                          <li class="menu__item">
                                                <a href="<?= $sItem->url ?>" class="menu__link"><?= $sItem->label ?></a>
                                          </li>
                                          <?php endforeach; ?>
                                    </ul>
                              <?php endif; ?>

                        </li>
                        <?php endforeach; ?>
                  </ul>
                  <?php endif; ?>

            </li>
      <?php endforeach; ?>
</ul>