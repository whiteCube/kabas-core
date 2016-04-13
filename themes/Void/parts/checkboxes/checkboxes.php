<div>
      <h2><?= $title ?></h2>
      <?php foreach($faveFoods as $checkbox): ?>
            <p>
                  <strong><?= $checkbox->isSelected() ? '●' : '○' ?> <?= $checkbox->label() ?></strong>
            </p>
      <?php endforeach; ?>

      <?php if($isAwesome->isSelected()): ?>
            <h3>Awesome mode activated!</h3>
            <img src="https://media.giphy.com/media/DpB9NBjny7jF1pd0yt2/giphy.gif" alt="Awesome mode" />
      <?php endif; ?>
</div>
