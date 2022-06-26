<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Rejestracja menu', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=register_menu">
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Slug</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (K3eRegisterMenu::loadMenus() as $menu_slug => $menu_args) { ?>
                        <tr>
                            <td>
                                <input type="text" name="RegisterMenu[<?= $menu_slug ?>][name]" class="regular-text ltr" value="<?= $menu_args['name'] ?>"/> 
                            </td>
                            <td>
                                <input type="text" name="RegisterMenu[<?= $menu_slug ?>][slug]" class="regular-text ltr" value="<?= $menu_args['slug'] ?>"/> 
                            </td>
                            <td>
                                <button class="button button-secondary remove-menu">Usuń</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr>
                            <h5>Dodaj nowe Menu</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="RegisterMenu[new][name]" class="regular-text ltr"/>
                        </td>
                        <td>
                            <input type="text" name="RegisterMenu[new][slug]" class="regular-text ltr"/>
                        </td>
                        <td>    

                        </td>
                    </tr>
                </tfoot>
            </table>
            <button class="button button-primary" type="submit">Zapisz</button>

        </form>
    </div>
</div>