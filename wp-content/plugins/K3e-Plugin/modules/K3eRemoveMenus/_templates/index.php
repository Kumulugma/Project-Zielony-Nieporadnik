<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Ukryj menu', 'k3e'); ?>
    </h1>

    <div class="card">
        <form method="post" action="admin.php?page=hide_menu">
            <fieldset>
                <?php foreach (K3eRemoveMenus::hide_menus() as $menu) { ?>
                    <p>
                        <input type="text" name="HideMenu[]" class="regular-text ltr" value="<?= $menu ?>"/> <button class="button button-secondary remove-menu">Znów widoczny</button>
                    </p>
                <?php } ?>
                <hr>
                <h5>Adres do ukrycia</h5>
                <p>
                    <input type="text" name="HideMenu[]" class="regular-text ltr"/>
                </p>
                <button class="button button-primary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
</div>