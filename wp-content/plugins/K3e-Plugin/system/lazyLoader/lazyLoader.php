<?php

class K3eLazyLoader {

    const VERSION = '0.1a';

    function __construct() {
        $preloader = unserialize(get_option('k3e_lazyloader'));

        if (is_admin()) {

            add_action('admin_menu', 'k3e_lazyloader');

            function k3e_lazyloader() {
                add_submenu_page(
                        'konfiguracja',
                        'LazyLoader', //page title
                        'LazyLoader', //menu title
                        'manage_options', //capability,
                        'lazyloader', //menu slug
                        'k3e_lazyloader_content' //callback function
                );
            }

            function k3e_lazyloader_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eLazyLoader::save();
        }

        if (get_option('k3e_lazyloader_activate')) {
            require_once 'themes/lazyloader.php';
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['LazyLoader'])) {
            if (isset($_POST['LazyLoader']['class'])) {
                $lazyloaderClass = ($_POST['LazyLoader']['class']);
                K3eSystem::setSettings('k3e_lazyloader_class', ($lazyloaderClass));
            }
            if (isset($_POST['LazyLoader']['activate'])) {
                $lazyloaderActivate = ($_POST['LazyLoader']['activate']);
                K3eSystem::setSettings('k3e_lazyloader_activate', $lazyloaderActivate);
            } else {
                K3eSystem::setSettings('k3e_lazyloader_activate', 0);
            }
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function getClass() {
        $lazyloaderClass = (get_option('k3e_lazyloader_class'));
        if (!$lazyloaderClass) {
            K3eSystem::setSettings('k3e_lazyloader_class', "", true);
            $lazyloaderClass = "";
        }
        return $lazyloaderClass;
    }

    public static function getStatus() {
        $lazyloaderActivate = (get_option('k3e_lazyloader_activate'));
        if (!$lazyloaderActivate) {
            K3eSystem::setSettings('k3e_lazyloader_activate', 0, true);
            $lazyloaderActivate = "";
        }
        return $lazyloaderActivate;
    }

}
