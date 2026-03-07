<?php
/**
 * WooCommerce — checkout/form-checkout.php
 * Página de checkout con diseño Street Editorial.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<div class="wrap mt-nav">
    <div class="wc-checkout-page">

        <p class="eyebrow" style="margin-bottom:12px;">— Finalizar compra</p>
        <h1 class="wc-checkout-title">Checkout</h1>

        <?php wc_print_notices(); ?>

        <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

        <!-- Formulario principal de WooCommerce -->
        <form name="checkout"
              method="post"
              class="checkout woocommerce-checkout"
              action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
              enctype="multipart/form-data">

            <div class="checkout-grid">

                <!-- Columna izquierda: datos del cliente -->
                <div>
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div id="customer_details">

                        <!-- Datos de facturación -->
                        <div style="margin-bottom:40px;">
                            <h3 style="font-size:28px;letter-spacing:3px;margin-bottom:24px;">Billing</h3>
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>

                        <!-- Datos de envío (si aplica) -->
                        <?php if ( WC()->cart->needs_shipping_address() ) : ?>
                            <div style="margin-bottom:40px;">
                                <h3 style="font-size:28px;letter-spacing:3px;margin-bottom:24px;">Shipping</h3>
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>
                        <?php endif; ?>

                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                    <!-- Notas adicionales -->
                    <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                </div>

                <!-- Columna derecha: resumen del pedido -->
                <div>
                    <div class="order-summary-box">
                        <h3>Order Summary</h3>

                        <table class="shop_table" style="margin-bottom:0;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="text-align:right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( WC()->cart->get_cart() as $key => $item ) :
                                    $p = $item['data'];
                                    if ( ! $p ) continue;
                                ?>
                                    <tr>
                                        <td style="color:var(--muted);">
                                            <?php echo esc_html( $p->get_name() ); ?>
                                            <span style="color:var(--dim);"> &times; <?php echo absint($item['quantity']); ?></span>
                                            <?php echo wc_get_formatted_cart_item_data( $item ); ?>
                                        </td>
                                        <td style="text-align:right;color:var(--accent);">
                                            <?php echo wc_price( $item['line_total'] ); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="font-size:10px;letter-spacing:3px;text-transform:uppercase;color:var(--muted);">Subtotal</th>
                                    <td style="text-align:right;font-size:13px;"><?php echo WC()->cart->get_cart_subtotal(); ?></td>
                                </tr>
                                <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                                    <tr>
                                        <th style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--muted);"><?php echo esc_html($fee->name); ?></th>
                                        <td style="text-align:right;"><?php echo wc_price( $fee->total ); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th style="font-size:12px;letter-spacing:3px;text-transform:uppercase;">Total</th>
                                    <td style="text-align:right;font-size:20px;color:var(--accent);">
                                        <?php echo WC()->cart->get_cart_total(); ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Métodos de pago y botón Place Order -->
                        <?php do_action( 'woocommerce_review_order_before_payment' ); ?>
                        <?php woocommerce_checkout_payment(); ?>
                        <?php do_action( 'woocommerce_review_order_after_payment' ); ?>
                    </div>
                </div>

            </div><!-- /checkout-grid -->

        </form>

        <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

    </div><!-- /wc-checkout-page -->
</div><!-- /wrap -->

<?php get_footer(); ?>
