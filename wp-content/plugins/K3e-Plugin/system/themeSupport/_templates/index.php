<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Wsparcie szablonu', 'k3e'); ?>
    </h1>

    <div class="card">
        <form method="post" action="admin.php?page=theme_support">
            <fieldset>
                <?php foreach (K3eThemeSupport::theme_support() as $theme_support => $args) { ?>
                    <p>
                        <input type="checkbox" id="<?= $theme_support ?>Form" name="ThemeSupport[<?= $theme_support ?>]" value="1" <?= ($args['status'] == 1) ? "checked" : "" ?>>
                        <label for="<?= $theme_support ?>Form">Funkcjonalność WP: <?= $args['name'] ?></label>
                    </p>
                <?php } ?>
                <button class="button button-primary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
</div>