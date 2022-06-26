<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Konfiguracja', 'k3e'); ?>
    </h1>


    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Dodane miniaturki</th></tr></thead>
                        <tbody>
                            <?php
                            $thumbnails = unserialize(get_option('k3e_thumbnail_sizes'));
                            if ($thumbnails) {
                                foreach ($thumbnails as $thumbnail) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $thumbnail['name'] ?></td>
                                        <td class="desc"><?= $thumbnail['width'] ?>x<?= $thumbnail['height'] ?> [<?= ($thumbnail['crop'] == 1) ? "Przycięte" : "Nie przycięte" ?>]</td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="importer-item">
                                    <td colspan="2" class="import-system">Brak nowych miniaturek</td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                    <br>

                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Wszystkie miniaturki</th></tr></thead>
                        <tbody>
                            <?php
                            global $_wp_additional_image_sizes;

                            $thumbnails = $_wp_additional_image_sizes;
                            if ($thumbnails) {
                                foreach ($thumbnails as $key => $thumbnail) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $key ?></td>
                                        <td class="desc"><?= $thumbnail['width'] ?>x<?= $thumbnail['height'] ?> [<?= ($thumbnail['crop'] == 1) ? "Przycięte" : "Nie przycięte" ?>]</td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="importer-item">
                                    <td colspan="2" class="import-system">Brak nowych miniaturek</td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th>Ukryte menu</th></tr></thead>
                        <tbody>
                            <?php
                            $menus = unserialize(get_option('k3e_hide_menu'));
                            if ($menus) {
                                foreach ($menus as $menu) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $menu ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="importer-item">
                                    <td class="import-system">Brak ukrytych menu</td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Wsparcie szablonów</th></tr></thead>
                        <tbody>
                            <?php
                            $supports = unserialize(get_option('k3e_theme_support'));
                            if ($supports) {
                                foreach ($supports as $support) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $support['name'] ?></td>
                                        <td class="import-system"><?= ($support['status'] == 1) ? "Włączone" : "---" ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="importer-item">
                                    <td colspan="2" class="import-system">Brak dodatkowego wsparcia w szablonie</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Mapa strony</th></tr></thead>
                        <tbody>
                            <?php
                            if (class_exists('K3eSitemap')) {
                                if (file_exists(get_home_path() . "sitemap.xml")) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><a href="<?= get_home_path() . "sitemap.xml" ?>"><?= get_home_path() . "sitemap.xml" ?></a></td>
                                        <td class="import-system">
                                            <small>
                                                <?= date(get_option('date_format'), filemtime(get_home_path() . "sitemap.xml")); ?>
                                                <?= date(get_option('time_format'), filemtime(get_home_path() . "sitemap.xml")); ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="2" class="import-system">Brak wygenerowanej mapy. Zapisz wpis lub stronę i spróbuj ponownie.</td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>