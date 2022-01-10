<footer class="footer space-ptb bg-dark">
    <div class="container">
        <div class="row pb-2">
            <div class="col-md-4">
                <div class="footer-useful-List">
                    <?php
                    wp_nav_menu(
                            array(
                                'menu' => 'Stopka - lewa',
                                'container' => false,
                                'items_wrap' => '<ul id="%1$s" class="list-unstyled mb-0">%3$s</ul>'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="col-md-4 text-left text-md-center">
                <a class="footer-logo" href="/">
                    <img class="img-fluid logo" src="<?= get_template_directory_uri() ?>/assets/images/logo-white.svg" alt="<?php bloginfo('name'); ?>">
                </a>
            </div>
            <div class="col-md-4 mt-4 mt-md-0 text-left text-md-end">
                <div class="footer-useful-List">
                    <?php
                    wp_nav_menu(
                            array(
                                'menu' => 'Stopka - prawa',
                                'container' => false,
                                'items_wrap' => '<ul id="%1$s" class="list-unstyled mb-0">%3$s</ul>',
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-0">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-5 text-center text-md-left my-2 my-md-0">
                    <p class="mb-0 text-white"> <?php bloginfo('name'); ?> &copy; Copyright <span id="copyright"> <?= date("Y") ?> </span> </p>
                    <p class="mt-1 text-white"> Realizacja: <a href="https://k3e.pl">K3e.pl</a> </p>
                </div>
            </div>
        </div>
    </div>
</footer>
