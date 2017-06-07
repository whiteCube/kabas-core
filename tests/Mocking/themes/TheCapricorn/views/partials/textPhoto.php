<section class="textGals">
    <div class="wrapper wrapper--thinner">
        
        <div class="textGals__gal">
            <?php foreach ($photos as $photo): ?>
            <figure class="textGals__photo">
                <img class="textGals__photo" src="<?= $photo->widen(374, function ($constraint) {$constraint->upsize(); })->src() ?>" alt="<?= $photo->alt; ?>">
            </figure>
            <?php endforeach ?>
        </div>

        <div class="textGals__content">
            <h2 class="textGals__title"><?= $title; ?></h2>
            <div class="textGals__text">
                
            </div>
            <ul class="textGals__links">
                <li class="textGals__linkItem">
                    <a class="" href=""></a>
                </li>
            </ul>
        </div>

    </div>
</section>