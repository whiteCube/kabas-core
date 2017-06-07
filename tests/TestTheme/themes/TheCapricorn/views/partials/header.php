<?php Part::head(); ?>

<header class="header">
    <div class="header__container">
        <div class="headerLogo">
            <figure class="headerLogo__figure">
            </figure>
            <a href="<?= Url::to('home'); ?>" class="headerLogo__link" title="Aller vers la page d’accueil">Retourner sur la page d’accueil</a>
        </div>
        <?php Part::nav(); ?>
    </div>
    <?php if (isset($banner)): ?>
            <?php $banner->render(); ?>
    <?php endif ?>

</header>

<main class="main">
