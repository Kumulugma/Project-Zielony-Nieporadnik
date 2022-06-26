<?php

class K3E {

    const PLUGIN_NAME = "K3e - Plugin";
    const PLUGIN_SLUG = "k3e_plugin";
    const PLUGIN_BOOTSTRAP = 'k3e-plugin';
    const PLUGIN_DIR = 'K3e-Plugin';
    const PLUGIN_URL = ABSPATH . 'wp-content/plugins/' . self::PLUGIN_DIR . '/';
    const PLUGIN_PAGE = 'konfiguracja';
    const PLUGIN_ICON = 'dashicons-schedule';
    const DEFAULT_MODULES = [
        'autoupdate' => ['status' => false, 'name' => 'Automatyczne aktualizacje', 'class' => 'K3eAutoupdate'],
        'thumbnails' => ['status' => false, 'name' => 'Miniaturki', 'class' => 'K3eThumbnails'],
        'hideAdminBar' => ['status' => false, 'name' => 'Ukryj belkę administracyjną', 'class' => 'K3eHideAdminBar'],
        'hideEmojis' => ['status' => false, 'name' => 'Ukryj emotki', 'class' => 'K3eHideEmojis'],
        'themeSupport' => ['status' => false, 'name' => 'Wsparcie szablonu', 'class' => 'K3eThemeSupport'],
        'themeDomain' => ['status' => false, 'name' => 'Domena z tłumaczeniem', 'class' => 'K3eThemeDomain'],
        'documentSeparator' => ['status' => false, 'name' => 'Separator w tytule', 'class' => 'K3eDocumentSeparator'],
        'jQueryDisable' => ['status' => false, 'name' => 'Wyłącz jQuery', 'class' => 'K3eJQueryDisable'],
        'registerMenu' => ['status' => false, 'name' => 'Zarejestruj menu', 'class' => 'K3eRegisterMenu'],
        'placeholders' => ['status' => false, 'name' => 'Zaślepka', 'class' => 'K3ePlaceholder'],
        'preloader' => ['status' => false, 'name' => 'Preloader', 'class' => 'K3ePreloader'],
        'lazyLoader' => ['status' => false, 'name' => 'LazyLoader', 'class' => 'K3eLazyLoader']
    ];
    const DEFAULT_THEME_SUPPORT = [
//        'admin-bar' => ['status' => false, 'name' => 'admin-bar'],
//        'align-wide' => ['status' => false, 'name' => 'align-wide'],
//        'automatic-feed-links' => ['status' => false, 'name' => 'automatic-feed-links'],
//        'core-block-patterns' => ['status' => false, 'name' => 'core-block-patterns'],
//        'custom-background' => ['status' => false, 'name' => 'custom-background'],
//        'custom-header' => ['status' => false, 'name' => 'custom-header'],
//        'custom-line-height' => ['status' => false, 'name' => 'custom-line-height'],
//        'custom-logo' => ['status' => false, 'name' => 'Personalizowane logo'],
//        'customize-selective-refresh-widgets' => ['status' => false, 'name' => 'customize-selective-refresh-widgets'],
//        'custom-spacing' => ['status' => false, 'name' => 'custom-spacing'],
//        'custom-units' => ['status' => false, 'name' => 'custom-units'],
//        'dark-editor-style' => ['status' => false, 'name' => 'dark-editor-style'],
//        'disable-custom-colors' => ['status' => false, 'name' => 'disable-custom-colors'],
//        'disable-custom-font-sizes' => ['status' => false, 'name' => 'disable-custom-font-sizes'],
//        'editor-color-palette' => ['status' => false, 'name' => 'editor-color-palette'],
//        'editor-gradient-presets' => ['status' => false, 'name' => 'editor-gradient-presets'],
//        'editor-font-sizes' => ['status' => false, 'name' => 'Edytor rozmiarów czcionek'],
//        'editor-styles' => ['status' => false, 'name' => 'Edytor stylów'],
//        'featured-content' => ['status' => false, 'name' => 'featured-content'],
        'html5' => ['status' => true, 'name' => 'HTML5'],
        'menus' => ['status' => false, 'name' => 'Konfigurator menu'],
//        'post-formats' => ['status' => false, 'name' => 'post-formats'],
        'post-thumbnails' => ['status' => true, 'name' => 'Obrazek wyróżniający'],
//        'responsive-embeds' => ['status' => false, 'name' => 'responsive-embeds'],
//        'starter-content' => ['status' => false, 'name' => 'starter-content'],
//        'title-tag' => ['status' => false, 'name' => 'Tytułt tagów'],
//        'wp-block-styles' => ['status' => false, 'name' => 'Style bloków'],
//        'widgets' => ['status' => false, 'name' => 'Widgety'],
//        'widgets-block-editor' => ['status' => false, 'name' => 'Edytor blokowy widgetów']
    ];
    const DEFAULT_THUMBNAILS = [
        'bar' => ['name' => 'bar', 'width' => '300', 'height' => '1300', 'crop' => true]
    ];
    const DEFAULT_THEME_DOMAIN = 'k3e';
    const DEFAULT_DOCUMENT_SEPARATOR = '|';
    const DEFAULT_MENUS = [];
}
