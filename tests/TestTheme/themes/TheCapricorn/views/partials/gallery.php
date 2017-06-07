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
			
		</figure>
		<?php endforeach ?>
	</div>
</section>