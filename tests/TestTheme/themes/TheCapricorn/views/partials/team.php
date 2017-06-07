<?php use TheCapricorn\Core\Utils\Phone as Phone;  ?>
<?php if ($persons): ?> 
<section class="team">
    <div class="wrapper">
        <h2 class="team__title"><?= $title; ?></h2>
        <div class="team__container">
            <?php foreach ($persons as $person): ?>
            <section class="teamItem">
                <h3 class="teamItem__name"><?= $person->name; ?></h3>
                <p class="teamItem__job"><?= $person->job; ?></p>
                <a class="teamItem__mail" href="mailto:<?= $person->mail; ?>"><?= $person->mail; ?></a>
                <a class="teamItem__phone" href="mailto:<?= Phone::format($person->phone); ?>"><?= $person->phone; ?></a>
                <figure class="teamItem__image">
                    <img src="<?= $person->photo->fit(375, 250, function ($constraint) { $constraint->upsize(); })->apply()->src(); ?>" alt="<?= $person->photo->alt(); ?>">
                </figure>
                <?php if ($person->social): ?>
                <div class="teamItem__social"><!--
                 --><div class="teamItem__socialCenter">
                    <?php foreach ($person->social as $item): ?>
                        <a class="teamItem__socialItem teamItem__socialItem--<?= $item->type->key(); ?>" href="<?= $item->url; ?>"><?= $item->label; ?></a>
                    <?php endforeach ?>
                    </div><!--
             --></div>    
                <?php endif ?>

            </section>       
            <?php endforeach ?>
        </div>
    </div>
</section>
<?php endif ?>
