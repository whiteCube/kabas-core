    <?php if (count($items)): ?>
    <ul class="socialNav"><!--
    <?php foreach($items as $item):?>
     --><li class="socialNav__item">
            <a  href="<?= $item->url;?>" 
                class="socialNav__link<?= ($item->icon ? ' socialNav__link--' . $item->icon : ''); ?>">
                <?= $item->label;?>
            </a>
        </li><!---
    <?php endforeach;?>
 --></ul>
    <?php endif ?>