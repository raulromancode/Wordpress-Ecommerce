<?php
/**
 * WooCommerce — cart/cart.php
 * Página de carrito con diseño Street Editorial.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<div class="wrap mt-nav">
    <div class="wc-cart-page">

        <p class="eyebrow" style="margin-bottom:12px;">— Tu carrito</p>
        <h1 class="wc-cart-title">Cart</h1>

        <?php wc_print_notices(); ?>
        <?php do_action( 'woocommerce_before_cart' ); ?>

        <form class="woocommerce-cart-form"
              action="<?php echo esc_url( wc_get_cart_url() ); ?>"
              method="post">

            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table" cellspacing="0">
                <thead>
                    <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name">Product</th>
                        <th class="product-price">Price</th>
                        <th class="product-quantity">Qty</th>
                        <th class="product-subtotal">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                            $permalink = apply_filters(
                                'woocommerce_cart_item_permalink',
                                $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '',
                                $cart_item, $cart_item_key
                            );
                    ?>
                        <tr class="woocommerce-cart-form__cart-item cart_item">

                            <!-- Eliminar -->
                            <td class="product-remove">
                                <?php echo apply_filters( 'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr__( 'Eliminar artículo', 'street-editorial' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                ); ?>
                            </td>

                            <!-- Miniatura -->
                            <td class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail',
                                    $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
                                echo $permalink
                                    ? '<a href="' . esc_url( $permalink ) . '">' . $thumbnail . '</a>'
                                    : $thumbnail;
                                ?>
                            </td>

                            <!-- Nombre -->
                            <td class="product-name" data-title="Producto">
                                <?php
                                echo $permalink
                                    ? '<a href="' . esc_url( $permalink ) . '">' . esc_html( $_product->get_name() ) . '</a>'
                                    : esc_html( $_product->get_name() );
                                echo wc_get_formatted_cart_item_data( $cart_item );
                                ?>
                            </td>

                            <!-- Precio -->
                            <td class="product-price" data-title="Precio">
                                <?php echo apply_filters( 'woocommerce_cart_item_price',
                                    WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                            </td>

                            <!-- Cantidad -->
                            <td class="product-quantity" data-title="Cantidad">
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    echo '1 <input type="hidden" name="cart[' . esc_attr($cart_item_key) . '][qty]" value="1">';
                                } else {
                                    woocommerce_quantity_input( [
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                        'min_value'    => '0',
                                        'product_name' => $_product->get_name(),
                                    ] );
                                }
                                ?>
                            </td>

                            <!-- Subtotal -->
                            <td class="product-subtotal" data-title="Total">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal',
                                    WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
                                    $cart_item, $cart_item_key ); ?>
                            </td>

                        </tr>
                    <?php endif; endforeach; ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                    <!-- Fila de acciones -->
                    <tr>
                        <td colspan="6" class="actions" style="padding-top:28px;">
                            <?php if ( wc_coupons_enabled() ) : ?>
                                <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
                                    <input type="text" name="coupon_code" id="coupon_code" value=""
                                           placeholder="Coupon code"
                                           style="background:var(--surface);border:1px solid var(--border);color:var(--white);padding:10px 14px;font-size:12px;flex:1;min-width:160px;">
                                    <button type="submit" class="btn btn-outline"
                                            name="apply_coupon" value="Apply coupon">
                                        Apply
                                    </button>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn-ghost"
                                    name="update_cart" value="Update cart"
                                    style="cursor:pointer;">
                                Update Cart
                            </button>
                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </td>
                    </tr>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <!-- Totales -->
        <div class="cart-collaterals">
            <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>

        <?php do_action( 'woocommerce_after_cart' ); ?>

    </div><!-- /wc-cart-page -->
</div><!-- /wrap -->

<?php get_footer(); ?>
