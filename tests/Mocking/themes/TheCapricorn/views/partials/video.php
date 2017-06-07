<section class="video" data-video-id="<?= $videoId; ?>" data-component="video">
    <h2 class="video__title"><?= $title; ?></h2>
    <div class="wrapper">
        <div class="video__container">
                <div class="videoOverlay" aria-hidden="true">
                    <a class="videoOverlay__link" href="#">Regarder cette vidéo</a>
                    <img class="videoOverlay__img" src="<?= $overlay->fit(800, 450, function ($constraint) {
        $constraint->upsize();
    })->apply()->src(); ?>" alt="<?= $overlay->alt(); ?>">
                </div>
                <div class="video__player"></div>
        </div>
    </div>

</section>