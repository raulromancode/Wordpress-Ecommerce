<?php
/**
 * Street Editorial — woo-custom.php
 * Personalizaciones de WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Columnas y productos por página ────────────────────── */
add_filter( 'loop_shop_columns',  fn() => 4 );
add_filter( 'loop_shop_per_page', fn() => 12, 20 );

/* ── Quitar breadcrumbs de WC ───────────────────────────── */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* ── Quitar sidebar de WC ───────────────────────────────── */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/* ── Clase body WC ───────────────────────────────────────── */
add_filter( 'body_class', function ( $classes ) {
    if ( is_woocommerce() || is_cart() || is_checkout() ) {
        $classes[] = 'wc-page';
    }
    return $classes;
} );


/**
 * Helper: renderiza la plantilla de una product card.
 * Llama a template-parts/product-card.php con el producto global.
 */
function se_render_product_card( $product_id = null ) {
    if ( $product_id ) {
        global $post, $product;
        $post    = get_post( $product_id );
        $product = wc_get_product( $product_id );
        setup_postdata( $post );
    }
    get_template_part( 'template-parts/product-card' );
    if ( $product_id ) wp_reset_postdata();
}
