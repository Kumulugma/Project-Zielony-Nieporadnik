<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Preloader', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=preloader">
            <fieldset>
                <p>
                    <label for="preloaderActivate">Uruchom preloader: </label>
                    <input type="checkbox" id="preloaderActivate" name="Preloader[activate]" value="1" <?= (K3ePreloader::getStatus() == 1) ? "checked" : "" ?> >
                    <input type="hidden" name="Preloader[token]" value="<?=md5(rand(1,255))?>"/>
                </p>

                <?php /*
                <label for="preloaderCSS">Treść:</label>
                <textarea rows="10" cols="50" class="large-text code" name="Preloader[css]" id="preloaderCSS"> <?= K3ePreloader::getCss() ?></textarea>
                */?>
                
                <button class="button button-primary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
</div>