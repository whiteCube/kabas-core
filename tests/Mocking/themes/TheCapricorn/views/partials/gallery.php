<section class="gallery" data-component="gallery">
	<div class="gallery__text">
		<h2 class="gallery__title" role="heading" aria-level="2" ><?= $title ;?></h2>
		<div  class="gallery__description">
			<?= $description ;?>
		</div>
	</div>
	<div class="gallery__items">
		<?php foreach ($images as $item): ?>
		<figure class="galleryItem">
			<a class="galleryItem__link" target="_blank" href="<?= $item->image->fit(1920, 1080,  function ($constraint) {$constraint->upsize();}); ?>">Voir cet image en plus grande résolution</a>		
			<img class="galleryItem__img" src="<?= $item->image->fit(340, 340) ?>" alt="<?= $item->image->alt(); ?>">
			<figcaption class="galleryItem__text"><!--
			 --><div class="galleryItem__textAlign">
					<span class="galleryItem__title"><?= $item->title; ?></span>
					<?php if ($item->description): ?>
					<span class="galleryItem__description"><?= $item->description; ?></span>
					<?php endif ?>
				</div><!--
		 --></figcaption>
		</figure>
		<?php endforeach ?>
	</div>
</section>