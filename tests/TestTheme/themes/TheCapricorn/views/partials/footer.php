    </main>
    <footer class="footer">
        <section class="wrapper">
            <h2 class="sro"><?= $title; ?></h2>
        
            <div class="footer__left">
                <figure class="footerLogo">
                    <a class="footerLogo__link" href="">Revenir à la page d’accueil</a>
                </figure>
                <dl class="footerInfos">
                    <dt class="sro">Téléphone</dt>
                    <dd class="footerInfos__item footerInfos__item--phone">
                        <a class="footerInfos__link" href="tel:"><?= $info->phone ?></a>
                    </dd>
                    
                    <dt class="sro">E-mail</dt>
                    <dd class="footerInfos__item footerInfos__item--email">
                    <a class="footerInfos__link" href="mailto:<?= $info->mail; ?>">
                    <?= $info->mail; ?>
                    </a>
                    </dd>
                    
                    <dt class="sro">Adresse</dt>
                    <dd class="footerInfos__item footerInfos__item--address">
                        <p><?= $info->address->streetNumber; ?></p>
                        <p><span><?= $info->address->postalCode; ?></span> <span><?= $info->address->city; ?></span></p>
                        <a  class="footerInfos__link"
                            href="http://maps.google.com/maps?daddr=<?= urlencode($info->address->streetNumber . ' ' . $info->address->postalCode . ' ' . $info->address->city ); ?>">
                            Voir l’adresse dans Google maps</a>
                    </dd>
                </dl>
            </div>

            <?php //Menu::footerNav(); ?>

            <section class="footerContact">
                <h3 class="footerContact__title"><?= $contact->title; ?></h3>
                <p class="footerContact__text"><?= $contact->text; ?></p>
                <a href="<?= $contact->link->url; ?>" class="footerContact__link"><?= $contact->link->label; ?></a>
            </section>
        </section>
    </footer>
<?php Part::foot(); ?>
