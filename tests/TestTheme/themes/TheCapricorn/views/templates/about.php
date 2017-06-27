<html>
	<head></head>
	<body>
		<h1>The about page</h1>
        <?php if(isset($phone)): ?>
        <a href="<?= $phone->href ?>"><?= $phone->label ?></a>
        <?php endif; ?>
	</body>
</html>