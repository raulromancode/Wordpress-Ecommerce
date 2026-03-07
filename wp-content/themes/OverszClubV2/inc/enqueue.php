<?php
/**
 * OverszClubV2 — enqueue.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function oc_enqueue_assets() {
    $v   = OC_VERSION;
    $uri = OC_URI;

    /* ── Google Fonts ──────────────────────────────────── */
    wp_enqueue_style(
        'oc-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap',
        [],
        null
    );

    /* ── Estilos ───────────────────────────────────────── */
    wp_enqueue_style( 'oc-style', get_stylesheet_uri(), ['oc-fonts'], $v );

    /* ── Scripts ───────────────────────────────────────── */
    wp_enqueue_script( 'oc-marquee',    $uri . '/assets/js/marquee.js',    [],                   $v, true );
    wp_enqueue_script( 'oc-animations', $uri . '/assets/js/animations.js', [],                   $v, true );
    wp_enqueue_script( 'oc-cart',       $uri . '/assets/js/cart.js',
        [ 'jquery', 'wc-add-to-cart' ], $v, true );

    /* ── Variables JS ──────────────────────────────────── */
    wp_localize_script( 'oc-cart', 'ocData', [
        'ajax_url'    => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'oc_nonce' ),
        'cart_url'    => function_exists('wc_get_cart_url')     ? wc_get_cart_url()     : home_url('/carrito/'),
        'checkout_url'=> function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/finalizar-compra/'),
        'i18n'        => [
            'added'      => __( '✓ Añadido al carrito', 'overszclubv2' ),
            'select_size'=> __( 'Selecciona una talla', 'overszclubv2' ),
            'adding'     => __( 'Añadiendo...',         'overszclubv2' ),
        ],
    ] );
}
add_action( 'wp_enqueue_scripts', 'oc_enqueue_assets' );

/* ── Quitar estilos por defecto de WooCommerce ─────────── */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
