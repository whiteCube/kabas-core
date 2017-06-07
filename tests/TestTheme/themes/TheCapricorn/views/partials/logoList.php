<?php use TheCapricorn\Core\ImageFilter\Logolist;?>

<section class="logoList" data-component="lSlider">
    <h2 role="heading" aria-level="2" class="sro"><?=$title;?></h2>
    <div class="logoList__logos"><!--

        <?php foreach ($logos as $logo): ?>
     --><section class="logoItem">
                     <a class="logoItem__link" href="<?=$logo->url;?>">Voir le site web de <?=$logo->name;?></a>
            <h3 role="heading" aria-level="3" class="logoItem__title"><?=$logo->name;?></h3>
            <figure class="logoItem__img">
            </figure>
        </section><!--
        <?php endforeach?>

 --></div>
</section>