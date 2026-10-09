<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MPDEF_THEME_VERSION' ) ) {
	define( 'MPDEF_THEME_VERSION', '1.0.0' );
}

if ( ! defined( 'MPDEF_PATH' ) ) {
	define( 'MPDEF_PATH', get_template_directory() );
}

if ( ! defined( 'MPDEF_URL' ) ) {
	define( 'MPDEF_URL', get_template_directory_uri() );
}

if ( ! defined( 'MPDEF_TEXT_DOMAIN' ) ) {
	define( 'MPDEF_TEXT_DOMAIN', 'mpdef' );
}

add_action( 'after_setup_theme', 'mpdef_theme_setup' );
function mpdef_theme_setup() {
    load_theme_textdomain( 'mpdef', MPDEF_PATH . '/languages' );

    add_theme_support( 'title-tag');

    add_theme_support( 'post-formats', 
		array(
			'link',
			'aside',
			'gallery',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		)
	);
    
    add_theme_support( 'post-thumbnails');
    
    add_theme_support( 'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        )
    );
    
    add_theme_support( 'custom-logo', array(
        'height'      => 50,
        'width'       => 50,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    register_nav_menus( array(
        'header_menu' => esc_html__( 'Header Menu', 'mpdef' ),
    ) );
}

add_action( 'wp_enqueue_scripts', 'mpdef_assets' );
function mpdef_assets() {
    // CSS
    wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-style', get_stylesheet_uri(), array(), MPDEF_THEME_VERSION );

    wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-reset', MPDEF_URL . '/assets/css/critical/reset.css', array(), MPDEF_THEME_VERSION );
    wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-fonts', MPDEF_URL . '/assets/css/critical/fonts.css', array(), MPDEF_THEME_VERSION );

    wp_enqueue_style( 'Swiper',                     MPDEF_URL . '/assets/css/critical/swiper-bundle.min.css',  array(), '14.1.0' );

    wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-main',  MPDEF_URL . '/assets/css/critical/main.css',  array(), MPDEF_THEME_VERSION );

    if ( is_home() || is_front_page() ) {
        wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-home-page',  MPDEF_URL . '/assets/css/home-page/home-page.css',  array(), MPDEF_THEME_VERSION );
    }

    if ( is_page( 'about' ) ) {
        wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-about-page',  MPDEF_URL . '/assets/css/about-page/about-page.css',  array(), MPDEF_THEME_VERSION );
    }

    // Для шаблону сторінки за замовчуванням (page.php)
    global $template;
    if ( is_page() && basename( $template ) === 'page.php' ) {
        wp_enqueue_style( MPDEF_TEXT_DOMAIN . '-default-page',  MPDEF_URL . '/assets/css/default-page/default-page.css',  array(), MPDEF_THEME_VERSION );
    }

    // JavaScript
    wp_enqueue_script( 'jquery' );

    wp_enqueue_script( 'Swiper',                    MPDEF_URL . '/assets/js/critical/swiper-bundle.min.js', array(), '14.1.0', true );

    wp_enqueue_script( MPDEF_TEXT_DOMAIN . '-main', MPDEF_URL . '/assets/js/critical/main.js', array(), MPDEF_THEME_VERSION, true );

    if ( is_home() || is_front_page() ) {
        wp_enqueue_script( MPDEF_TEXT_DOMAIN . '-home-page', MPDEF_URL . '/assets/js/home-page/home-page.js', array(), MPDEF_THEME_VERSION, true );
    }
}

if ( ! function_exists( 'mpdef_render_menu' ) ) {
    function mpdef_render_menu() {
        if ( has_nav_menu( 'header_menu' ) ) {
            wp_nav_menu( 
                array(
                    'theme_location'  => 'header_menu',
                    'container'       => 'nav',
                    'container_class' => 'header__menu menu',
                    'menu_class'      => '',
                    'fallback_cb'     => false,
                )
            );

            return;
        }
    }
}

require_once MPDEF_PATH . '/inc/template-functions.php';
require_once MPDEF_PATH . '/inc/seo.php';
require_once MPDEF_PATH . '/inc/optimize.php';
require_once MPDEF_PATH . '/inc/security.php';