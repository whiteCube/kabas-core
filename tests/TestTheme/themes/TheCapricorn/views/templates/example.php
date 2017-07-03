<html>
	<head></head>
	<body>
        <?= Part::header(); ?>
		<ul>
            <?php foreach($list as $item): ?>
                <li><?= $item->title ?></li>
            <?php endforeach; ?>
        </ul>

        <?= Lang::trans('foo.trans'); ?>

        <form action="http://localhost/Kabas/core/tests/TestTheme/public/foo/bar" enctype="multipart/form-data" method="POST">
            <div>
                <label for="file">File</label>
                <input name="userfile" type="file">
                <input type="submit" value="Send">
            </div>
        </form>
	</body>
</html>