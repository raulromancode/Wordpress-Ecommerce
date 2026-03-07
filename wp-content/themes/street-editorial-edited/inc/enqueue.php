<?php
/**
 * Street Editorial — enqueue.php
 * Carga de scripts y estilos
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function se_enqueue_assets() {
    $v   = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    /* ── Fuentes Google ─────────────────────────────────── */
    wp_enqueue_style(
        'se-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
        [],
        null
    );

    /* ── Estilos ─────────────────────────────────────────── */
    wp_enqueue_style( 'se-base',  get_stylesheet_uri(),            [],     $v );
    wp_enqueue_style( 'se-theme', $uri . '/assets/css/theme.css',  ['se-base'], $v );

    /* ── Scripts (footer=true) ───────────────────────────── */
    // marquee primero (no depende de jQuery)
    wp_enqueue_script( 'se-marquee',    $uri . '/assets/js/marquee.js',    [],        $v, true );
    wp_enqueue_script( 'se-animations', $uri . '/assets/js/animations.js', [],        $v, true );
    wp_enqueue_script( 'se-cart',       $uri . '/assets/js/cart.js',       ['jquery', 'wc-add-to-cart'], $v, true );

    /* ── Variables para JS ───────────────────────────────── */
    wp_localize_script( 'se-cart', 'streetEditorial', [
        'ajax_url'   => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'se_nonce' ),
        'cart_url'   => wc_get_cart_url(),
        'checkout_url' => wc_get_checkout_url(),
    ] );
}
add_action( 'wp_enqueue_scripts', 'se_enqueue_assets' );

/* ── Eliminar estilos por defecto de WooCommerce ─────────── */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
