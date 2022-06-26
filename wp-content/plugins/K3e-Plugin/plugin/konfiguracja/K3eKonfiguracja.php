<?php
if (!defined('ABSPATH'))
    exit;

/**
 *
 */
class K3eKonfiguracja {

    
    public static function run() {

        add_action('admin_menu', initPlugin::PLUGIN_SLUG . '_menu');

        function k3e_plugin_menu() {
            add_menu_page(
                    __('Konfiguracja', 'k3e'), //Title
                    __('Konfiguracja', 'k3e'), //Name
                    'manage_options',
                    initPlugin::PLUGIN_PAGE,
                    initPlugin::PLUGIN_SLUG . '_content',
                    initPlugin::PLUGIN_ICON,
                    3
            );

            /* Dostępne pozycje

              2 – Dashboard
              4 – Separator
              5 – Posts
              10 – Media
              15 – Links
              20 – Pages
              25 – Comments
              59 – Separator
              60 – Appearance
              65 – Plugins
              70 – Users
              75 – Tools
              80 – Settings
              99 – Separator

             */
        }

        function k3e_plugin_content() {
            K3E::renderView('templates/index');
        }

    }

    public static function save() {
        if (isset($_POST['Form'])) {
            $form = [];
            foreach (initPlugin::DEFAULT_MODULES as $module => $value) {
                if (isset($_POST['Form'][$module])) {
                    $form[$module] = $_POST['Form'][$module];
                } else {
                    $form[$module] = '0';
                }
            }
            K3E::setSettings('k3e_plugin_modules', serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function modules() {
        $modules = unserialize(get_option('k3e_plugin_modules'));
        if (!$modules) {
            K3E::setSettings('k3e_plugin_modules', serialize(initPlugin::DEFAULT_MODULES), true);
            $modules = initPlugin::DEFAULT_MODULES;
        }
        return $modules;
    }

}
