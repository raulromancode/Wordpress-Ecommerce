<?php
/**
 * OverszClubV2 — woo-functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Columnas y productos por página ───────────────────── */
add_filter( 'loop_shop_columns',  fn() => 4 );
add_filter( 'loop_shop_per_page', fn() => 12, 20 );

/* ── Quitar elementos por defecto ──────────────────────── */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/* ── Body class ────────────────────────────────────────── */
add_filter( 'body_class', function( $classes ) {
    if ( is_woocommerce() || is_cart() || is_checkout() ) {
        $classes[] = 'wc-page';
    }
    return $classes;
} );

/* ── Fragmentos del carrito ────────────────────────────── */
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

    $fragments['#oc-cart-count'] = '<span id="oc-cart-count" class="cart-badge" style="display:' . ( $count > 0 ? 'flex' : 'none' ) . '">' . absint($count) . '</span>';
    $fragments['.cart-drawer__badge'] = '<span class="cart-drawer__badge">' . absint($count) . '</span>';

    return $fragments;
} );

/* ── Textos en español ──────────────────────────────────── */
add_filter( 'woocommerce_product_add_to_cart_text', fn() => 'Añadir al carrito' );
add_filter( 'woocommerce_product_single_add_to_cart_text', fn() => 'Añadir al carrito' );

/* ── Badge de oferta ────────────────────────────────────── */
add_filter( 'woocommerce_sale_flash', function( $text, $post, $product ) {
    return '<span class="product-card__badge badge-sale">Oferta</span>';
}, 10, 3 );
