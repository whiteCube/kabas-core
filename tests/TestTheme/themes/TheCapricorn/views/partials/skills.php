<section class="skills">
	<h2 role="heading" aria-level="2" class="skills__title"><?= $title; ?></h2>
	<div class="skills__wrapper">
	<?php foreach ($list as $skill): ?>
		<section class="skill skill--<?= $skill->icon ;?>">
			<h3 class="skill__name" role="heading" aria-level="3"><?= $skill->name; ?></h3>
			<p class="skill__text"><?= $skill->description ;?></p>
			<span href="<?= $skill->link;?>" class="skill__fakeButton"><?= $skill->linkLabel;?> <span>sur <?= $skill->name;?></span></span>
			<a href="#skill" class="skill__link"></a>
		</section>
	<?php endforeach; ?>
	</div>
</section>
