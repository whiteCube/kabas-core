<section class="intro">
	<div class="intro__wrapper">
		<div class="intro__content">
			<h2 role="heading" aria-level="2" class="intro__title"><?= $title; ?></h2>
			<div class="intro__text"><?= $text ;?></div>
            <div class="intro__links"><!--
                <?php foreach ($links as $link): ?>
                 --><a href="<?= $link->url; ?>" class="intro__link"><?= $link->label; ?></span></a><!--
                <?php endforeach ?>
         --></div>

		</div>
		<figure class="intro__figure">
			<img src="<?= $image->resize(null, 399, function ($constraint) {$constraint->aspectRatio();})->apply()->src(); ?>" width="265" height="399" alt="" class="intro__figure__img">
		</figure>
	</div>
	
</section>
