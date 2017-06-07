<!DOCTYPE html>
<html lang="<?= Lang::getCurrent()->locale; ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title><?= Page::title(); ?></title>
		<?php Assets::here('header', 'css/app.css'); ?>
        <?php Assets::here('header2', 'js/front.js'); ?>

		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>
	<body class="<?= Page::template(); ?>" data-page="<?= Page::template(); ?>">



