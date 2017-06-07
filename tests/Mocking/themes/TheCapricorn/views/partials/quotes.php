<?php if (count($items)): ?>
<section class="quotes" data-component="quotes">

	<h2 class="sro" role="heading" aria-level="2"><?= $title; ?></h2>

	<ul class="quotesPagers"><!--
	<?php foreach ($items as $key => $quote): ?>
	 --><li class="quotesPagers__item">
		<a class="quotesPagers__link" href="#quote-<?= $key?>">Voir la citation <?= $quote->title; ?></a>
		</li><!--
		<?php endforeach ?>
 --></ul>
    
    <div class="quotes__list">
	<?php foreach ($items as $key => $quote): ?>
	<section class="quote" id="quote-<?= $key?>">
		<h3 class="quote__title" role="heading" aria-level="3"><?= $quote->title; ?></h3>
		<blockquote class="quote__text">
			<?= $quote->text; ?>
			<cite class="quote__author">
				<?php if ($quote->author->url->get()): ?><a class="quote__authorLink" href="<?= $quote->author->url ?>"><?php endif ?>
				<?= $quote->author->name; ?>
				<?php if ($quote->author->url->get()): ?></a><?php endif ?>		
			</cite>
		</blockquote>
	</section>		
	<?php endforeach ?>
    </div>
</section>
<?php endif ?>