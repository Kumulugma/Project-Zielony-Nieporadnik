<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('LazyLoader', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=lazyloader">
            <fieldset>
                <p>
                    <label for="lazyloaderActivate">Uruchom LazyLoader: </label>
                    <input type="checkbox" id="lazyloaderActivate" name="LazyLoader[activate]" value="1" <?= (K3eLazyLoader::getStatus() == 1) ? "checked" : "" ?> >
                    <input type="hidden" name="LazyLoader[token]" value="<?=md5(rand(1,255))?>"/>
                </p>

                <?php /*
                <label for="lazyloaderClass">Klasa CSS:</label>
                <input type="text" name="LazyLoader[class]" id="lazyloaderClass" value="<?= K3eLazyLoader::getClass() ?>" />
                */ ?>
                <button class="button button-primary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
</div>