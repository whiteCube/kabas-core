<ul class="menu">
      <?php foreach($menu as $menuItem): ?>
            <li>
                  <a href="<?= $menuItem->url ?>"><?= $menuItem->label ?></a>
                  <?php if($menuItem->hasSubmenu()): ?>
                        <ul>
                              <?php foreach($menuItem->subitems as $subItem): ?>
                              <li>
                                    <a href="<?= $subItem->url ?>">
                                          <?= $subItem->label ?>
                                    </a>
                                    <?php if($subItem->hasSubmenu()): ?>
                                          <ul>
                                                <?php foreach($subItem->subitems as $subItem): ?>
                                                <li>
                                                      <a href="<?= $subItem->url ?>">
                                                            <?= $subItem->label ?>
                                                      </a>

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
