<div class="banner" style="background-image:url('<?= $background->fit(1920,1080)->apply()->src();  ?>')"><!--
 --><div class="banner__center">
        <h1 class="banner__title" role="heading" aria-level="1"><?= Page::title(); ?></h1>
        <?php if ($subtitle->get()): ?>
            <span class="banner__small"><?= $subtitle; ?></span>
        <?php endif ?>
    </div><!--
--></div>