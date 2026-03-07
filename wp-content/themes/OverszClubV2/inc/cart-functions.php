<?php
/**
 * OverszClubV2 — cart-functions.php
 * AJAX handlers para el carrito lateral.
 * 100% compatible con WooCommerce nativo.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ══════════════════════════════════════════════════════════
   HTML DEL DRAWER
   ══════════════════════════════════════════════════════════ */
function oc_build_cart_html() : string {
    if ( ! WC()->cart || WC()->cart->is_empty() ) {
        return '<div class="cart-empty">
            <svg fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <p>Tu carrito está vacío</p>
        </div>';
    }

    ob_start();
    foreach ( WC()->cart->get_cart() as $key => $item ) {
        $product  = $item['data'];
        $img      = get_the_post_thumbnail_url( $item['product_id'], 'thumbnail' );
        $name     = $product->get_name();
        $qty      = $item['quantity'];
        $subtotal = wc_price( $item['line_total'] );
        $size     = '';

        // Leer talla desde variación o meta personalizado
        if ( ! empty( $item['variation'] ) ) {
            foreach ( $item['variation'] as $attr => $val ) {
                if ( $val ) {
                    $size = ucfirst( str_replace( ['attribute_pa_', 'attribute_'], '', $attr ) ) . ': ' . strtoupper( $val );
                    break;
                }
            }
        }
        if ( ! $size && ! empty( $item['oc_size'] ) ) {
            $size = 'Talla: ' . strtoupper( $item['oc_size'] );
        }
        ?>
        <div class="cart-item" data-key="<?php echo esc_attr( $key ); ?>">
            <img class="cart-item__img"
                 src="<?php echo esc_url( $img ?: wc_placeholder_img_src() ); ?>"
                 alt="<?php echo esc_attr( $name ); ?>"
                 loading="lazy">
            <div class="cart-item__info">
                <p class="cart-item__name"><?php echo esc_html( $name ); ?></p>
                <p class="cart-item__meta">
                    <?php if ( $size ) echo esc_html( $size ) . ' · '; ?>
                    x<?php echo absint( $qty ); ?>
                </p>
                <div class="cart-item__bottom">
                    <span class="cart-item__price"><?php echo $subtotal; ?></span>
                    <button class="cart-item__remove"
                            data-key="<?php echo esc_attr( $key ); ?>"
                            aria-label="<?php esc_attr_e( 'Eliminar', 'overszclubv2' ); ?>">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    return ob_get_clean();
}

/* ══════════════════════════════════════════════════════════
   AJAX: fragmento completo del carrito
   ══════════════════════════════════════════════════════════ */
function oc_ajax_cart_fragment() {
    wp_send_json_success( [
        'html'  => oc_build_cart_html(),
        'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
        'total' => WC()->cart ? strip_tags( WC()->cart->get_cart_total() ) : '0,00 €',
    ] );
}
add_action( 'wp_ajax_oc_cart_fragment',        'oc_ajax_cart_fragment' );
add_action( 'wp_ajax_nopriv_oc_cart_fragment', 'oc_ajax_cart_fragment' );

/* ══════════════════════════════════════════════════════════
   AJAX: eliminar item
   ══════════════════════════════════════════════════════════ */
function oc_ajax_remove_item() {
    check_ajax_referer( 'oc_nonce', 'nonce' );
    $key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    if ( $key ) {
        WC()->cart->remove_cart_item( $key );
    }
    wp_send_json_success( [
        'html'  => oc_build_cart_html(),
        'count' => WC()->cart->get_cart_contents_count(),
        'total' => strip_tags( WC()->cart->get_cart_total() ),
    ] );
}
add_action( 'wp_ajax_oc_remove_item',        'oc_ajax_remove_item' );
add_action( 'wp_ajax_nopriv_oc_remove_item', 'oc_ajax_remove_item' );
