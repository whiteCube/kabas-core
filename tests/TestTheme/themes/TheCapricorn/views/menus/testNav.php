<ul>
    <?php foreach($items as $item): ?>
        <li>
            <?= $item->label ?>
            <?php if($item->hasSub()): ?>
                <ul>
                <?php foreach($item->items as $subitem): ?>
                    <li><?= $subitem->label ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>