<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Separator Tytułu', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=document_separator">
            <table>
                <thead>
                    <tr>
                        <th>Wartość</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>
                                <input type="text" name="DocumentSeparator" class="regular-text ltr" value="<?= K3eDocumentSeparator::separator() ?>"/> 
                            </td>
                        </tr>
                </tbody>
            </table>
            <button class="button button-primary" type="submit">Zapisz</button>

        </form>
    </div>
</div>