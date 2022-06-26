<?php

class K3ePreloader {

    const VERSION = '0.1a';

    function __construct() {
        $preloader = unserialize(get_option('k3e_preloader'));

        if (is_admin()) {

            add_action('admin_menu', 'k3e_preloader');

            function k3e_preloader() {
                add_submenu_page(
                        'konfiguracja',
                        'Preloader', //page title
                        'Preloader', //menu title
                        'manage_options', //capability,
                        'preloader', //menu slug
                        'k3e_preloader_content' //callback function
                );
            }

            function k3e_preloader_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3ePreloader::save();
        }

        if (get_option('k3e_preloader_activate')) {
            require_once 'themes/preloader.php';
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['Preloader'])) {
            if (isset($_POST['Preloader']['css'])) {
                $preloaderCss = ($_POST['Preloader']['css']);
                K3eSystem::setSettings('k3e_preloader_css', ($preloaderCss));
            }
            if (isset($_POST['Preloader']['activate'])) {
                $preloaderActivate = ($_POST['Preloader']['activate']);
                K3eSystem::setSettings('k3e_preloader_activate', $preloaderActivate);
            } else {
                K3eSystem::setSettings('k3e_preloader_activate', 0);
            }
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function getCss() {
        $preloaderCss = (get_option('k3e_preloader_css'));
        if (!$preloaderCss) {
            K3eSystem::setSettings('k3e_preloader_css', "", true);
            $preloaderCss = "";
        }
        return $preloaderCss;
    }

    public static function getStatus() {
        $preloaderActivate = (get_option('k3e_preloader_activate'));
        if (!$preloaderActivate) {
            K3eSystem::setSettings('k3e_preloader_activate', 0, true);
            $preloaderActivate = "";
        }
        return $preloaderActivate;
    }

}
