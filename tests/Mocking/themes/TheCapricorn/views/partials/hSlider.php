<div class="hSlider" data-component="hSlider">
    <h2 class="hidden" role="heading" aria-level="2"><?= $title ?></h2>
    <div class="hSlider__list">
        <?php foreach ($slides as $key => $slide): ?>
        <section class="hSliderItem" id="<?= 'hSlider-' . $key; ?>" style="background-image: url('<?= $slide->image->fit(1920, 1000,function ($constraint) {$constraint->upsize();})->apply()->src(); ?>');">
            
            <div class="wrapper">
                <div class="hSliderItem__box">
                    <strong class="hSliderItem__subTitle"><?= $slide->subTitle ;?></strong>
                    <h3 class="hSliderItem__title" role="heading" aria-level="3"><?= $slide->title; ?></h3>
                    <p class="hSliderItem__description"><?= $slide->description ;?></p>
                    <a class="hSliderItem__link" href="<?= $slide->link->url ;?>"><?= $slide->link->label ;?></a>
                </div>
            </div>
            <?php if (0): //canceld for now?> 
            <figure class="hSliderItem__figure">
                <img class="hSliderItem__img" 
                     src="<?= $slide->image->fit(1920, 1000,function ($constraint) {$constraint->upsize();})->apply()->src(); ?>" 
                     alt="<?= $slide->image->alt() ?>">
            </figure>   
            <?php endif ?>
        </section>
        <?php endforeach; ?>
    </div>

    <div class="hSlider__pagers">
        <div class="wrapper"><!--
            <?php foreach ($slides as $key => $slide): ?>
         --><a class="hSlider__pager" href="<?= '#hSlider-' . $key; ?>"><?= $slide->title; ?></a><!--
            <?php endforeach; ?>
     --></div>
    </div> 

</div>
