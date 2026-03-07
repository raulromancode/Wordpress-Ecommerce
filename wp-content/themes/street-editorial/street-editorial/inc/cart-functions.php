<?php
/**
 * Street Editorial — cart-functions.php
 * AJAX handlers para el carrito lateral
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Genera el HTML de los items del drawer.
 */
function se_build_cart_items_html() : string {
    $cart = WC()->cart;

    if ( $cart->is_empty() ) {
        return '<div class="cart-empty">
                  <svg fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                  </svg>
                  <p>Tu carrito está vacío</p>
                </div>';
    }

    ob_start();
    foreach ( $cart->get_cart() as $key => $item ) {
        $product = $item['data'];
        $img     = get_the_post_thumbnail_url( $item['product_id'], 'thumbnail' );
        $name    = $product->get_name();
        $qty     = $item['quantity'];
        $sub     = wc_price( $item['line_total'] );
        $meta    = '';
        if ( ! empty( $item['variation'] ) ) {
            foreach ( $item['variation'] as $attr => $val ) {
                $meta .= ucfirst( str_replace( 'attribute_pa_', '', $attr ) ) . ': ' . esc_html( $val ) . '&nbsp;&nbsp;';
            }
        }
        ?>
        <div class="cart-item">
            <img class="cart-item__img"
                 src="<?php echo esc_url( $img ?: wc_placeholder_img_src() ); ?>"
                 alt="<?php echo esc_attr( $name ); ?>">
            <div class="cart-item__info">
                <p class="cart-item__name"><?php echo esc_html( $name ); ?></p>
                <?php if ( $meta ) : ?>
                    <p class="cart-item__meta"><?php echo wp_kses_post( $meta ); ?> &middot; ×<?php echo $qty; ?></p>
                <?php else : ?>
                    <p class="cart-item__meta">Cantidad: <?php echo $qty; ?></p>
                <?php endif; ?>
                <p class="cart-item__price"><?php echo $sub; ?></p>
            </div>
            <button class="cart-item__remove" data-key="<?php echo esc_attr( $key ); ?>" aria-label="Eliminar">&times;</button>
        </div>
        <?php
    }
    return ob_get_clean();
}


/**
 * AJAX: devuelve fragmento completo del carrito.
 */
function se_ajax_cart_fragment() {
    wp_send_json_success( [
        'items_html' => se_build_cart_items_html(),
        'count'      => WC()->cart->get_cart_contents_count(),
        'total'      => strip_tags( WC()->cart->get_cart_total() ),
    ] );
}
add_action( 'wp_ajax_se_cart_fragment',        'se_ajax_cart_fragment' );
add_action( 'wp_ajax_nopriv_se_cart_fragment', 'se_ajax_cart_fragment' );


/**
 * AJAX: elimina un item del carrito.
 */
function se_ajax_remove_item() {
    $key = sanitize_text_field( $_POST['cart_key'] ?? '' );
    if ( $key ) {
        WC()->cart->remove_cart_item( $key );
    }
    wp_send_json_success();
}
add_action( 'wp_ajax_se_remove_item',        'se_ajax_remove_item' );
add_action( 'wp_ajax_nopriv_se_remove_item', 'se_ajax_remove_item' );
