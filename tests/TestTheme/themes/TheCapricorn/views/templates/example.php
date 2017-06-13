<html>
	<head></head>
	<body>
        <?= Part::header(); ?>
		<ul>
            <?php foreach($list as $item): ?>
                <li><?= $item->title ?></li>
            <?php endforeach; ?>
        </ul>
	</body>
</html>