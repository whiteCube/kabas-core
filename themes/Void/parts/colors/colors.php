<div class="colors">
      <h2><?= $title; ?></h2>
      <p>
            <strong>Color:</strong>
            <?= $color1 ?>
            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: <?= $color1->hex(); ?>;"></span>
      </p>
      <p>
            <strong>Red:</strong>
            <?= $color1->red() ?>
      </p>
      <p>
            <strong>Green:</strong>
            <?= $color1->green() ?>
      </p>
      <p>
            <strong>Blue:</strong>
            <?= $color1->blue() ?>
      </p>
</div>
