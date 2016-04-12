<div class="colors">
      <h2><?= $title; ?></h2>
      <p>
            <strong>Initial timestamp:</strong> <?= $date ?>
      </p>
      <p>
            <code>$date->diffForHumans() → </code> <?= $date->diffForHumans() ?>
      </p>
      <p>
            <code>$date->diffInDays() → </code> <?= $date->diffInDays() ?>
      </p>
      <p>
            <code>$date->toFormattedDateString() → </code> <?= $date->toFormattedDateString() ?>
      </p>
      <p>
            <code>$date->format('l jS \\of F Y, H:i') → </code> <?= $date->format('l jS \\of F Y, H:i') ?>
      </p>

</div>
