<?php

class K3ePlaceholder {

    const VERSION = '0.1a';

    function __construct() {
        $placeholders = unserialize(get_option('k3e_placeholders'));

        if (is_admin()) {

            add_action('admin_menu', 'k3e_placeholder');

            function k3e_placeholder() {
                add_submenu_page(
                        'konfiguracja',
                        'Zaślepka', //page title
                        'Zaślepka', //menu title
                        'manage_options', //capability,
                        'placeholder', //menu slug
                        'k3e_placeholder_content' //callback function
                );
            }

            function k3e_placeholder_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3ePlaceholder::save();
        }

//        echo get_option('k3e_placeholder_activate');
//        exit;
        if (get_option('k3e_placeholder_activate')) {
            require_once 'themes/page-placeholder.php';
            require_once 'shortcodes/K3E_PLACEHOLDER.php';
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['Placeholder'])) {
            if (isset($_POST['Placeholder']['name'])) {
                $placeholderName = html_entity_decode($_POST['Placeholder']['name']);
                K3eSystem::setSettings('k3e_placeholder_name', serialize($placeholderName));
            }
            if (isset($_POST['Placeholder']['amount'])) {
                $placeholderAmount = intval($_POST['Placeholder']['amount']);
                K3eSystem::setSettings('k3e_placeholder_amount', serialize($placeholderAmount));
            }
            if (isset($_POST['Placeholder']['activate'])) {
                $placeholderActivate = ($_POST['Placeholder']['activate']);
                K3eSystem::setSettings('k3e_placeholder_activate', $placeholderActivate);
            } else {
                K3eSystem::setSettings('k3e_placeholder_activate', 0);
            }
            $save = TRUE;
        }

        if (isset($_POST['Placeholders'])) {
            $form = [];
            foreach ($_POST['Placeholders'] as $placeholders => $value) {
                $form[$placeholders] = ['headling' => html_entity_decode($value['headling']), 'content' => html_entity_decode($value['content']), 'active' => intval($value['active'])];
            }

            K3eSystem::setSettings('k3e_placeholders', serialize($form));
            $save = TRUE;
        }
        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function getPlaceholders() {
        $placeholders = unserialize(get_option('k3e_placeholders'));
        if (!$placeholders) {
            K3eSystem::setSettings('k3e_placeholders', serialize([0 => ['headling' => '', 'content' => '', 'active' => 0]]), true);
            $placeholders = [0 => ['headling' => '', 'content' => '', 'active' => 0]];
        }
        return $placeholders;
    }

    public static function getAmount() {
        $placeholdersAmount = unserialize(get_option('k3e_placeholder_amount'));
        if (!$placeholdersAmount) {
            K3eSystem::setSettings('k3e_placeholder_amount', serialize(""), true);
            $placeholdersAmount = "";
        }
        return $placeholdersAmount;
    }

    public static function getName() {
        $placeholderName = unserialize(get_option('k3e_placeholder_name'));
        if (!$placeholderName) {
            K3eSystem::setSettings('k3e_placeholder_name', serialize(""), true);
            $placeholderName = "";
        }
        return $placeholderName;
    }

    public static function getStatus() {
        $placeholderActivate = (get_option('k3e_placeholder_activate'));
        if (!$placeholderActivate) {
            K3eSystem::setSettings('k3e_placeholder_activate', 0, true);
            $placeholderActivate = "";
        }
        return $placeholderActivate;
    }

}
