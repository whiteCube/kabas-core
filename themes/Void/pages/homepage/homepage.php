<?php Part::header(); ?>

<h2><?= $title ?></h2>
<p>
      <?= $intro->baseHeading(4)->length(200) ?>
</p>





<?php var_dump($number1->floatval() + $number2->i); ?>




<?php Part::get('news'); ?>
<?php Part::get('dates'); ?>
<?php Part::get('colors'); ?>
<?php Part::get('links'); ?>
<?php Part::get('images'); ?>
<?php Part::get('checkboxes'); ?>
<?php Part::get('section1'); ?>

<?php Part::footer(); ?>
